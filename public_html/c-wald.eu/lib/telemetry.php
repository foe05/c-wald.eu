<?php
declare(strict_types=1);

/**
 * Shared telemetry helper — server-side event dispatch to log.broetzens.de.
 *
 * The log API expects a fixed, minimal JSON schema:
 *
 *   { "tool": "c-wald.eu", "tool_version": "1.0.0",
 *     "instance": "https://c-wald.eu", "event": "<name>" }
 *
 * Events emitted by this site:
 *   - index.php           → site_loaded
 *   - rostock/index.php   → rostock_loaded
 *   - rostock/submit.php  → rostock_waitinglist
 *
 * NO personal data is transmitted: no IP address, no user agent, no form
 * fields — only the fact that a named event occurred. The payload is
 * identical for every visitor.
 *
 * Bot / non-human traffic is filtered out before an event is queued:
 * page views require a GET request (HEAD-based uptime monitors and scanners
 * are dropped) and the User-Agent is matched against a crawler blocklist.
 * The User-Agent is inspected locally only and is never part of the payload.
 *
 * Dispatch is fire-and-forget from the shutdown handler (plus
 * fastcgi_finish_request() under PHP-FPM), so the user-facing response is
 * fully flushed before the outbound HTTPS call runs. Short curl timeouts
 * cap the worst-case worker hold time if the log endpoint is slow.
 *
 * All errors go to the PHP error log only — a failure here must never
 * affect the user-facing response.
 *
 * Direct web access to this file is blocked by lib/.htaccess.
 */

// Config is gitignored and lives only on the server (uploaded via SFTP).
// If it's missing — first deploy, or someone forgot — we fall back to
// safe defaults so the site keeps working with telemetry silently off.
$cwald_telemetry_config = __DIR__ . '/telemetry-config.php';
if (is_file($cwald_telemetry_config)) {
    require_once $cwald_telemetry_config;
}
unset($cwald_telemetry_config);

if (!defined('CWALD_TELEMETRY_ENABLED'))  define('CWALD_TELEMETRY_ENABLED',  false);
if (!defined('CWALD_TELEMETRY_DEBUG'))    define('CWALD_TELEMETRY_DEBUG',    false);
if (!defined('CWALD_TELEMETRY_ENDPOINT')) define('CWALD_TELEMETRY_ENDPOINT', 'https://log.broetzens.de/api/log');
if (!defined('CWALD_TELEMETRY_API_KEY'))  define('CWALD_TELEMETRY_API_KEY',  '');

// Identify this site in the shared log API. These have sensible defaults,
// so they don't need to live in telemetry-config.php. Bump TOOL_VERSION
// when the telemetry contract changes.
if (!defined('CWALD_TELEMETRY_TOOL'))     define('CWALD_TELEMETRY_TOOL',     'c-wald.eu');
if (!defined('CWALD_TELEMETRY_VERSION'))  define('CWALD_TELEMETRY_VERSION',  '1.0.0');
if (!defined('CWALD_TELEMETRY_INSTANCE')) define('CWALD_TELEMETRY_INSTANCE', 'https://c-wald.eu');

/**
 * Decide whether the current request looks like a bot / non-human client.
 *
 * The User-Agent is inspected locally only — it is NEVER added to the
 * payload or transmitted anywhere. An empty UA is treated as a bot, since
 * real browsers practically always send one.
 *
 * @param string $ua  Raw User-Agent header.
 */
function cwald_telemetry_is_bot(string $ua): bool {
    if ($ua === '') {
        return true;
    }
    $haystack = strtolower($ua);

    // Substrings that reliably appear in crawler / scanner / scripting /
    // link-preview clients but not in mainstream browser UAs.
    static $needles = [
        'bot', 'crawl', 'spider', 'slurp', 'scrap',
        'curl', 'wget', 'python', 'go-http', 'java/', 'libwww',
        'okhttp', 'headless', 'phantom', 'puppeteer', 'playwright',
        'preview', 'fetch', 'monitor', 'uptime', 'pingdom', 'statuscake',
        'facebookexternalhit', 'embedly', 'feedfetcher', 'feedburner',
        'archive.org', 'ia_archiver', 'semrush', 'ahrefs', 'mj12',
        'dotbot', 'dataprovider', 'censys', 'masscan', 'zgrab', 'nmap',
    ];
    foreach ($needles as $needle) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Queue a telemetry event for fire-and-forget dispatch on shutdown.
 *
 * Page-view events are only counted for real, human-looking GET requests:
 * HEAD-based uptime monitors / scanners are dropped via the method allowlist,
 * and well-known bots are dropped via the User-Agent check. The waitlist
 * submission passes $allowedMethods = ['POST'].
 *
 * @param string       $event           Event name, e.g. 'site_loaded'.
 * @param list<string> $allowedMethods  HTTP methods that may emit this event.
 */
function cwald_telemetry_send(string $event, array $allowedMethods = ['GET']): void {
    if (!CWALD_TELEMETRY_ENABLED) {
        return;
    }

    // Drop non-human traffic before building the payload. Page views must be
    // GET; HEAD (monitors, scanners) and other methods are ignored.
    $method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? ''));
    if ($method !== '' && !in_array($method, $allowedMethods, true)) {
        if (CWALD_TELEMETRY_DEBUG) {
            error_log('[c-wald telemetry] skipped event=' . $event . ' (method=' . $method . ' not allowed)');
        }
        return;
    }

    // Drop well-known crawlers / scanners / preview bots.
    if (cwald_telemetry_is_bot((string)($_SERVER['HTTP_USER_AGENT'] ?? ''))) {
        if (CWALD_TELEMETRY_DEBUG) {
            error_log('[c-wald telemetry] skipped event=' . $event . ' (bot user-agent)');
        }
        return;
    }

    if (CWALD_TELEMETRY_API_KEY === '' || CWALD_TELEMETRY_ENDPOINT === '') {
        if (CWALD_TELEMETRY_DEBUG) {
            error_log('[c-wald telemetry] skipped event=' . $event . ' (endpoint or api key not configured)');
        }
        return;
    }

    $payload = [
        'tool'         => CWALD_TELEMETRY_TOOL,
        'tool_version' => CWALD_TELEMETRY_VERSION,
        'instance'     => CWALD_TELEMETRY_INSTANCE,
        'event'        => $event,
    ];

    register_shutdown_function(static function () use ($payload): void {
        // Flush the response to the browser before the outbound call, so the
        // user perceives zero added latency.
        if (function_exists('fastcgi_finish_request')) {
            @fastcgi_finish_request();
        }
        cwald_telemetry_dispatch($payload);
    });
}

/**
 * POST the payload to log.broetzens.de. Runs on shutdown.
 *
 * @param array<string,mixed> $payload
 */
function cwald_telemetry_dispatch(array $payload): void {
    $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($body === false) {
        error_log('[c-wald telemetry] json_encode failed for event ' . ($payload['event'] ?? '?'));
        return;
    }

    if (!function_exists('curl_init')) {
        error_log('[c-wald telemetry] curl extension missing — telemetry disabled');
        return;
    }

    $ch = curl_init(CWALD_TELEMETRY_ENDPOINT);
    if ($ch === false) {
        return;
    }

    curl_setopt_array($ch, [
        CURLOPT_POST              => true,
        CURLOPT_POSTFIELDS        => $body,
        CURLOPT_HTTPHEADER        => [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Api-Key: ' . CWALD_TELEMETRY_API_KEY,
            'User-Agent: c-wald-telemetry/' . CWALD_TELEMETRY_VERSION . ' (+https://c-wald.eu)',
        ],
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_CONNECTTIMEOUT_MS => 800,
        CURLOPT_TIMEOUT_MS        => 1500,
        CURLOPT_NOSIGNAL          => true,
        CURLOPT_SSL_VERIFYPEER    => true,
        CURLOPT_SSL_VERIFYHOST    => 2,
    ]);

    $response = curl_exec($ch);
    $status   = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $err      = curl_error($ch);
    curl_close($ch);

    if ($status >= 200 && $status < 300) {
        if (CWALD_TELEMETRY_DEBUG) {
            error_log('[c-wald telemetry] ok event=' . ($payload['event'] ?? '?') . ' status=' . $status);
        }
        return;
    }

    error_log(sprintf(
        '[c-wald telemetry] dispatch failed event=%s status=%d curl_err=%s body=%s',
        (string) ($payload['event'] ?? '?'),
        $status,
        str_replace(["\r", "\n"], ' ', $err),
        is_string($response) ? str_replace(["\r", "\n"], ' ', substr($response, 0, 200)) : ''
    ));
}
