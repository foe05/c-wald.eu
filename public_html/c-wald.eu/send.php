<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function respond(bool $success, string $message, int $code = 200): void {
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
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

$fromDomain = 'c-wald.eu';
$fromAddress = 'no-reply@' . $fromDomain;
$encodedName = '=?UTF-8?B?' . base64_encode('C-Wald Website') . '?=';

$headers = [
    'From: ' . $encodedName . ' <' . $fromAddress . '>',
    'Reply-To: ' . $email,
    'Return-Path: ' . $fromAddress,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion(),
];

$encodedSubject = '=?UTF-8?B?' . base64_encode($mailSubject) . '?=';

$sent = @mail($recipient, $encodedSubject, $body, implode("\r\n", $headers), '-f' . $fromAddress);

if (!$sent) {
    respond(false, 'Mail delivery failed. Please email hallo@c-wald.de directly.', 500);
}

respond(true, 'Thanks — we will be in touch shortly.');
