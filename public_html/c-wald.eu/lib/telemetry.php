<?php
declare(strict_types=1);

/**
 * Shared telemetry helper — server-side event dispatch to log.broetzens.de.
 *
 * Used by:
 *   - index.php            → cwald.pageview         (path=/)
 *   - rostock/index.php    → cwald.pageview         (path=/rostock/)
 *   - rostock/submit.php   → cwald.waitlist.signup  (path=/rostock/)
 *
 * Design notes:
 *
 *   - Fire-and-forget. We register the dispatch on the shutdown handler so
 *     the response is fully sent before we talk to the log API. Under
 *     PHP-FPM we additionally call fastcgi_finish_request() to flush the
 *     response to the browser BEFORE the curl call runs. Under CGI / Apache
 *     module the shutdown handler still runs after PHP closes the response
 *     stream, so the user never waits.
 *
 *   - Short curl timeouts as a backstop: even on the shutdown handler we
 *     do not want a hung log endpoint to keep the FPM worker tied up.
 *
 *   - IP addresses are hashed with HMAC-SHA256 using a server-side pepper
 *     and a daily salt. Same IP yields the same hash within one UTC day
 *     (useful for unique-visitor counts) but cannot be correlated across
 *     days or reversed without the pepper. No raw IP ever leaves the host.
 *
 *   - Bot user agents are classified in the payload (ua_class = "bot" |
 *     "mobile" | "desktop") rather than dropped, so the log API can filter.
 *
 *   - All errors are sent to the PHP error log only. A failure here must
 *     NEVER affect the user-facing response.
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

if (!defined('CWALD_TELEMETRY_ENABLED'))   define('CWALD_TELEMETRY_ENABLED',  false);
if (!defined('CWALD_TELEMETRY_DEBUG'))     define('CWALD_TELEMETRY_DEBUG',    false);
if (!defined('CWALD_TELEMETRY_ENDPOINT'))  define('CWALD_TELEMETRY_ENDPOINT', '');
if (!defined('CWALD_TELEMETRY_API_KEY'))   define('CWALD_TELEMETRY_API_KEY',  '');
if (!defined('CWALD_TELEMETRY_IP_PEPPER')) define('CWALD_TELEMETRY_IP_PEPPER', '');

/**
 * Queue a telemetry event for fire-and-forget dispatch on shutdown.
 *
 * @param string              $event  Event type, e.g. 'cwald.pageview.home'.
 * @param array<string,mixed> $props  Arbitrary event properties. Must be
 *                                    JSON-serialisable; do NOT pass PII.
 */
function cwald_telemetry_send(string $event, array $props = []): void {
    if (!CWALD_TELEMETRY_ENABLED) {
        return;
    }

    // Snapshot request context NOW — by the time the shutdown handler runs,
    // $_SERVER is still populated but we capture explicitly for clarity.
    $payload = cwald_telemetry_build_payload($event, $props);

    register_shutdown_function(static function () use ($payload): void {
        // Flush the response to the browser before we make the outbound call,
        // so the user perceives zero added latency.
        if (function_exists('fastcgi_finish_request')) {
            @fastcgi_finish_request();
        }
        cwald_telemetry_dispatch($payload);
    });
}

/**
 * Build the JSON-ready payload from the current request context.
 *
 * @param array<string,mixed> $props
 * @return array<string,mixed>
 */
function cwald_telemetry_build_payload(string $event, array $props): array {
    $ua    = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
    $ip    = cwald_telemetry_client_ip();
    $path  = cwald_telemetry_request_path();

    // Referrer is optional and may contain a query string with PII on the
    // OTHER site. Strip query and fragment to be safe.
    $ref = (string)($_SERVER['HTTP_REFERER'] ?? '');
    if ($ref !== '') {
        $parts = @parse_url($ref);
        if (is_array($parts) && !empty($parts['host'])) {
            $ref = ($parts['scheme'] ?? 'https') . '://' . $parts['host']
                 . ($parts['path'] ?? '');
        } else {
            $ref = '';
        }
    }

    $payload = [
        'event'     => $event,
        'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        'source'    => 'c-wald.eu',
        'path'      => $path,
        'ip_hash'   => cwald_telemetry_hash_ip($ip),
        'ua'        => mb_substr($ua, 0, 500),
        'ua_class'  => cwald_telemetry_classify_ua($ua),
        // Cast to (object) so an empty props array serialises to {} not [],
        // keeping the JSON shape stable for the consuming log API.
        'props'     => (object) $props,
    ];

    if ($ref !== '') {
        $payload['referrer'] = mb_substr($ref, 0, 500);
    }

    return $payload;
}

/**
 * Best-effort client IP from REMOTE_ADDR.
 *
 * We intentionally ignore X-Forwarded-For: Hetzner shared hosting is not
 * behind a trusted reverse proxy for this site, so XFF can be spoofed.
 */
function cwald_telemetry_client_ip(): string {
    return (string)($_SERVER['REMOTE_ADDR'] ?? '');
}

/**
 * HMAC-SHA256 the IP with a server pepper + UTC day. Truncated to 16 hex
 * chars (64 bits) — plenty to distinguish visitors within a day while
 * limiting log size.
 *
 * Empty IP (CLI / unknown) hashes to a fixed placeholder so we never
 * accidentally emit a hash of the empty string.
 */
function cwald_telemetry_hash_ip(string $ip): string {
    if ($ip === '') {
        return '0000000000000000';
    }
    $daySalt = gmdate('Y-m-d');
    $digest  = hash_hmac('sha256', $ip, CWALD_TELEMETRY_IP_PEPPER . '|' . $daySalt);
    return substr($digest, 0, 16);
}

/**
 * Cheap UA classification — 'bot', 'mobile', or 'desktop'.
 *
 * The bot list is intentionally short; the log API can do deeper
 * classification if needed. We just want to flag the obvious cases so
 * top-line pageview numbers aren't grossly inflated.
 */
function cwald_telemetry_classify_ua(string $ua): string {
    if ($ua === '') {
        return 'unknown';
    }
    if (preg_match('~bot|crawler|spider|wget|curl|python-requests|httpclient|headlesschrome|slurp|facebookexternalhit|embedly|pingdom|uptimerobot~i', $ua)) {
        return 'bot';
    }
    if (preg_match('~Mobile|Android|iPhone|iPad|iPod|Opera Mini|IEMobile~', $ua)) {
        return 'mobile';
    }
    return 'desktop';
}

/**
 * Normalize REQUEST_URI to just the path portion, no query string.
 */
function cwald_telemetry_request_path(): string {
    $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
    $q = strpos($uri, '?');
    if ($q !== false) {
        $uri = substr($uri, 0, $q);
    }
    return $uri !== '' ? $uri : '/';
}

/**
 * Actually POST the payload to log.broetzens.de. Runs on shutdown.
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
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-API-Key: ' . CWALD_TELEMETRY_API_KEY,
            'User-Agent: c-wald-telemetry/1.0 (+https://c-wald.eu)',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT_MS => 800,
        CURLOPT_TIMEOUT_MS        => 1500,
        CURLOPT_NOSIGNAL          => true,
        CURLOPT_SSL_VERIFYPEER    => true,
        CURLOPT_SSL_VERIFYHOST    => 2,
    ]);

    $response = curl_exec($ch);
    $status   = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
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
        (string)($payload['event'] ?? '?'),
        $status,
        str_replace(["\r", "\n"], ' ', $err),
        is_string($response) ? str_replace(["\r", "\n"], ' ', substr($response, 0, 200)) : ''
    ));
}
