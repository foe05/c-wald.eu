<?php
declare(strict_types=1);

// Capture anything PHP might emit (warnings, notices) so our JSON stays clean.
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function respond(bool $success, string $message, int $code = 200): void {
    if (ob_get_level() > 0) { ob_end_clean(); }
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

function sanitizeForLog(string $s): string {
    return str_replace(["\r", "\n"], [' ', ' '], $s);
}

function logSubmission(array $fields, array $meta): void {
    $line = sprintf(
        "[%s] ip=%s mail_ok=%s mail_err=%s\n  name=%s\n  org=%s\n  email=%s\n  subject=%s\n  message=%s\n---\n",
        gmdate('Y-m-d H:i:s') . 'Z',
        sanitizeForLog((string)($meta['ip'] ?? 'unknown')),
        !empty($meta['mail_ok']) ? 'yes' : 'no',
        sanitizeForLog((string)($meta['mail_err'] ?? '')),
        sanitizeForLog($fields['name']),
        sanitizeForLog($fields['organisation']),
        sanitizeForLog($fields['email']),
        sanitizeForLog($fields['subject']),
        sanitizeForLog($fields['message'])
    );
    // Intentionally silenced: we don't want log-write failures to break the response.
    // The .htaccess in the same dir denies web access to *.log files.
    @file_put_contents(__DIR__ . '/submissions.log', $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$recipient = 'hallo@c-wald.de';

$allowedSubjects = [
    'MRV Consulting',
    'Carbon Credit Partnership',
    'Investor Inquiry',
    'Press / Media',
    'Other',
];

$name         = trim((string)($_POST['name'] ?? ''));
$organisation = trim((string)($_POST['organisation'] ?? ''));
$email        = trim((string)($_POST['email'] ?? ''));
$subject      = trim((string)($_POST['subject'] ?? ''));
$message      = trim((string)($_POST['message'] ?? ''));
$honeypot     = trim((string)($_POST['website'] ?? ''));

if ($honeypot !== '') {
    // Silently drop bot submissions (don't log — would flood the file).
    respond(true, 'Thanks — we will be in touch shortly.');
}

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    respond(false, 'Please fill in all required fields.', 400);
}
if (mb_strlen($name) > 200 || mb_strlen($organisation) > 200 || mb_strlen($email) > 200 || mb_strlen($message) > 8000) {
    respond(false, 'Input too long.', 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.', 400);
}
if (!in_array($subject, $allowedSubjects, true)) {
    respond(false, 'Please select a valid subject.', 400);
}

foreach ([$name, $organisation, $email, $subject] as $field) {
    if (preg_match('/[\r\n]/', $field)) {
        respond(false, 'Invalid input detected.', 400);
    }
}

$mailSubject = '[C-Wald] ' . $subject . ' — ' . $name;

$bodyLines = [
    'New contact form submission — c-wald.eu',
    str_repeat('=', 48),
    '',
    'Name:         ' . $name,
    'Organisation: ' . ($organisation !== '' ? $organisation : '—'),
    'E-Mail:       ' . $email,
    'Subject:      ' . $subject,
    '',
    'Message:',
    '--------',
    $message,
    '',
    str_repeat('-', 48),
    'Submitted: ' . gmdate('Y-m-d H:i:s') . ' UTC',
    'IP:        ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
];
$body = implode("\r\n", $bodyLines);

$fromDomain  = 'c-wald.eu';
$fromAddress = 'no-reply@' . $fromDomain;
$encodedName = '=?UTF-8?B?' . base64_encode('C-Wald Website') . '?=';

// Note: Return-Path is set by the receiving MTA per RFC 5321 — don't set it client-side.
// The envelope sender is controlled via the '-f' additional parameter below instead.
$headers = [
    'From: ' . $encodedName . ' <' . $fromAddress . '>',
    'Reply-To: ' . $email,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion(),
];

$encodedSubject = '=?UTF-8?B?' . base64_encode($mailSubject) . '?=';

// Clear any pending PHP error so error_get_last() reflects mail()'s outcome.
error_clear_last();

// Intentionally NOT using '@' — we want warnings captured by the server's
// PHP error log so we can see *why* mail() fails (e.g. sendmail missing,
// -f rejected, disabled_functions, etc).
$sent = mail(
    $recipient,
    $encodedSubject,
    $body,
    implode("\r\n", $headers),
    '-f' . $fromAddress
);

$lastErr = '';
if (!$sent) {
    $e = error_get_last();
    $lastErr = $e ? (string)($e['message'] ?? '') : '(mail() returned false, no PHP error captured)';
    error_log('[c-wald send.php] mail() failed: ' . sanitizeForLog($lastErr));
}

logSubmission(
    [
        'name'         => $name,
        'organisation' => $organisation,
        'email'        => $email,
        'subject'      => $subject,
        'message'      => $message,
    ],
    [
        'ip'       => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        'mail_ok'  => $sent,
        'mail_err' => $lastErr,
    ]
);

if (!$sent) {
    respond(false, 'Mail delivery failed. Please email hallo@c-wald.de directly.', 500);
}

respond(true, 'Thanks — we will be in touch shortly.');
