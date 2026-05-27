<?php
declare(strict_types=1);

// Capture anything PHP might emit (warnings, notices) so our JSON stays clean.
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/lib/mailer.php';

function respond(bool $success, string $message, int $code = 200): void {
    if (ob_get_level() > 0) { ob_end_clean(); }
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$recipient = 'hallo@c-wald.eu';

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

$result = cwald_mail_send(
    $recipient,
    'C-Wald Website',
    'hallo@c-wald.eu',
    $email,
    $mailSubject,
    implode("\r\n", $bodyLines)
);

if (!$result['sent']) {
    error_log('[c-wald send.php] mail() failed: ' . cwald_sanitize_for_log($result['error']));
}

cwald_mail_log(
    __DIR__ . '/submissions.log',
    [
        'name'    => $name,
        'org'     => $organisation,
        'email'   => $email,
        'subject' => $subject,
        'message' => $message,
    ],
    [
        'ip'       => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        'mail_ok'  => $result['sent'],
        'mail_err' => $result['error'],
    ]
);

if (!$result['sent']) {
    respond(false, 'Mail delivery failed. Please email hallo@c-wald.eu directly.', 500);
}

respond(true, 'Thanks — we will be in touch shortly.');
