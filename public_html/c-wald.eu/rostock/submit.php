<?php
declare(strict_types=1);

// Capture anything PHP might emit so our JSON stays clean.
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

function logWaitlistEntry(array $fields, array $meta): void {
    $line = sprintf(
        "[%s] ip=%s mail_ok=%s mail_err=%s\n  name=%s\n  email=%s\n  flaeche=%s\n  region=%s\n  traegerschaft=%s\n  einwilligung=%s\n---\n",
        gmdate('Y-m-d H:i:s') . 'Z',
        sanitizeForLog((string)($meta['ip'] ?? 'unknown')),
        !empty($meta['mail_ok']) ? 'yes' : 'no',
        sanitizeForLog((string)($meta['mail_err'] ?? '')),
        sanitizeForLog($fields['name']),
        sanitizeForLog($fields['email']),
        sanitizeForLog($fields['flaeche']),
        sanitizeForLog($fields['region']),
        sanitizeForLog($fields['traegerschaft']),
        sanitizeForLog($fields['einwilligung'])
    );
    // Silenced — log-write failure should not break the user response.
    // The site-level .htaccess denies web access to *.log.
    @file_put_contents(__DIR__ . '/waitlist.log', $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$recipient = 'hallo@c-wald.eu';

$allowedTraegerschaft = [
    '', // optional field, empty allowed
    'Privatwald',
    'Kommunalwald',
    'Stiftung',
    'Landesforst',
    'Sonstige',
];

$name          = trim((string)($_POST['name'] ?? ''));
$email         = trim((string)($_POST['email'] ?? ''));
$flaeche       = trim((string)($_POST['flaeche'] ?? ''));
$region        = trim((string)($_POST['region'] ?? ''));
$traegerschaft = trim((string)($_POST['traegerschaft'] ?? ''));
$einwilligung  = trim((string)($_POST['einwilligung'] ?? ''));
$honeypot      = trim((string)($_POST['website'] ?? ''));

// Honeypot: silently accept bot submissions so they don't retry.
if ($honeypot !== '') {
    respond(true, 'Danke. Sie stehen auf der Liste.');
}

if ($name === '' || $email === '' || $flaeche === '' || $region === '') {
    respond(false, 'Bitte füllen Sie alle Pflichtfelder aus.', 400);
}
if ($einwilligung !== 'ja') {
    respond(false, 'Bitte bestätigen Sie die Einwilligung zur Speicherung Ihrer Angaben.', 400);
}
if (mb_strlen($name) > 200 || mb_strlen($email) > 200
    || mb_strlen($flaeche) > 50 || mb_strlen($region) > 100
    || mb_strlen($traegerschaft) > 50) {
    respond(false, 'Eingabe zu lang.', 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Bitte geben Sie eine gültige E-Mail-Adresse an.', 400);
}
if (!in_array($traegerschaft, $allowedTraegerschaft, true)) {
    respond(false, 'Bitte wählen Sie eine gültige Trägerschaft.', 400);
}

// CRLF-injection guard on any field that might end up in a mail header.
foreach ([$name, $email, $flaeche, $region, $traegerschaft] as $field) {
    if (preg_match('/[\r\n]/', $field)) {
        respond(false, 'Ungültige Eingabe erkannt.', 400);
    }
}

$mailSubject = '[C-Wald Rostock] Warteliste: ' . $name;

$bodyLines = [
    'Neue Warteliste-Eintragung — c-wald.eu/rostock',
    str_repeat('=', 48),
    '',
    'Name:          ' . $name,
    'E-Mail:        ' . $email,
    'Waldfläche:    ' . $flaeche,
    'Region:        ' . $region,
    'Trägerschaft:  ' . ($traegerschaft !== '' ? $traegerschaft : '— (keine Angabe)'),
    'Einwilligung:  ' . ($einwilligung === 'ja' ? 'ja (DSGVO-Checkbox aktiv)' : 'NEIN'),
    '',
    str_repeat('-', 48),
    'Submitted: ' . gmdate('Y-m-d H:i:s') . ' UTC',
    'IP:        ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
    'UA:        ' . sanitizeForLog((string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown')),
];
$body = implode("\r\n", $bodyLines);

// Use the real, existing mailbox as both the From and the envelope sender.
// A non-existent envelope sender (e.g. no-reply@…) can cause Hetzner's MTA
// to silently drop the message after mail() has returned true.
$fromDomain  = 'c-wald.eu';
$fromAddress = 'hallo@' . $fromDomain;
$encodedName = '=?UTF-8?B?' . base64_encode('C-Wald Warteliste') . '?=';

$headers = [
    'From: ' . $encodedName . ' <' . $fromAddress . '>',
    'Reply-To: ' . $email,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion(),
];

$encodedSubject = '=?UTF-8?B?' . base64_encode($mailSubject) . '?=';

error_clear_last();

// Intentionally not silenced — we want warnings in the PHP error log so we can
// debug mail() failures (sendmail missing, -f rejected, disabled_functions, etc).
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
    error_log('[c-wald rostock/submit.php] mail() failed: ' . sanitizeForLog($lastErr));
}

logWaitlistEntry(
    [
        'name'          => $name,
        'email'         => $email,
        'flaeche'       => $flaeche,
        'region'        => $region,
        'traegerschaft' => $traegerschaft,
        'einwilligung'  => $einwilligung,
    ],
    [
        'ip'       => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        'mail_ok'  => $sent,
        'mail_err' => $lastErr,
    ]
);

if (!$sent) {
    // The log file still has the entry, so the lead isn't lost — but tell the user.
    respond(false, 'Eintragung gespeichert, aber Mail-Versand fehlgeschlagen. Bitte schreiben Sie an hallo@c-wald.eu.', 500);
}

respond(true, 'Danke. Sie stehen auf der Liste.');
