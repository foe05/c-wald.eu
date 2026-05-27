<?php
declare(strict_types=1);

/**
 * Shared mail-dispatch and submission-logging helpers for the c-wald.eu PHP
 * endpoints.
 *
 * Used by:
 *   - send.php                — investor contact form (en, top-level)
 *   - rostock/submit.php      — Rostock waitlist form  (de, /rostock/)
 *
 * Both forms hand off via PHP's mail() to Hetzner's local MTA. The settings
 * here are tuned for that environment:
 *
 *   - From-header AND '-f' envelope sender both point to a real, existing
 *     mailbox (hallo@c-wald.eu). A non-existent envelope sender — e.g.
 *     no-reply@… — causes Hetzner's MTA to silently drop the message after
 *     mail() has already returned true, because the bounce path is dead.
 *     Lesson learned the hard way (commit 2077785).
 *
 *   - Subject is RFC 2047 base64-encoded so UTF-8 special characters survive.
 *
 *   - Reply-To is the submitter's address, so 'Reply' from the inbox just
 *     works.
 *
 * Direct web access to this file is blocked by the adjacent .htaccess. Even
 * without that, fetching this file produces no output since it only declares
 * functions.
 */

/**
 * Replace CR/LF in a string so it's safe to write into a single-line log
 * entry and safe to use anywhere near a mail header (header-injection guard).
 */
function cwald_sanitize_for_log(string $s): string {
    return str_replace(["\r", "\n"], [' ', ' '], $s);
}

/**
 * Send a plain-text UTF-8 email via PHP's mail() with Hetzner-friendly headers.
 *
 * @return array{sent: bool, error: string}
 *         'error' is non-empty only when 'sent' is false; it captures the
 *         most recent PHP error so the caller can log it.
 */
function cwald_mail_send(
    string $recipient,
    string $fromName,
    string $fromAddress,
    string $replyTo,
    string $subjectPlain,
    string $bodyPlain
): array {
    $encodedName    = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subjectPlain) . '?=';

    $headers = [
        'From: ' . $encodedName . ' <' . $fromAddress . '>',
        'Reply-To: ' . $replyTo,
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'X-Mailer: PHP/' . phpversion(),
    ];

    // Clear any pending PHP error so error_get_last() below reflects mail()'s
    // outcome — and intentionally don't silence the call with '@', so warnings
    // (sendmail missing, '-f' rejected, disabled_functions, …) land in the
    // PHP error log where we can find them.
    error_clear_last();

    $sent = mail(
        $recipient,
        $encodedSubject,
        $bodyPlain,
        implode("\r\n", $headers),
        '-f' . $fromAddress
    );

    $error = '';
    if (!$sent) {
        $e = error_get_last();
        $error = $e
            ? (string)($e['message'] ?? '')
            : '(mail() returned false, no PHP error captured)';
    }

    return ['sent' => $sent, 'error' => $error];
}

/**
 * Append one submission record to the per-form log file. Caller passes
 * arbitrary field=>value pairs and they're written verbatim (with CR/LF
 * stripped) — that way each form can log whatever fields make sense.
 *
 * The log file MUST sit in a directory whose .htaccess blocks web access
 * to '*.log' (the site-level .htaccess at public_html/c-wald.eu/.htaccess
 * already does this).
 *
 * Failures are silenced: a log-write error must never break the user's
 * response.
 */
function cwald_mail_log(string $logPath, array $fields, array $meta): void {
    $header = sprintf(
        "[%s] ip=%s mail_ok=%s mail_err=%s\n",
        gmdate('Y-m-d H:i:s') . 'Z',
        cwald_sanitize_for_log((string)($meta['ip']       ?? 'unknown')),
        !empty($meta['mail_ok']) ? 'yes' : 'no',
        cwald_sanitize_for_log((string)($meta['mail_err'] ?? ''))
    );

    $body = '';
    foreach ($fields as $key => $value) {
        $body .= '  ' . $key . '=' . cwald_sanitize_for_log((string)$value) . "\n";
    }

    @file_put_contents($logPath, $header . $body . "---\n", FILE_APPEND | LOCK_EX);
}
