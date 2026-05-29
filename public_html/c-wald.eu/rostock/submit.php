<?php
declare(strict_types=1);

// Capture anything PHP might emit so our JSON stays clean.
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../lib/mailer.php';
require_once __DIR__ . '/../lib/telemetry.php';

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
    'UA:        ' . cwald_sanitize_for_log((string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown')),
];

$result = cwald_mail_send(
    $recipient,
    'C-Wald Warteliste',
    'hallo@c-wald.eu',
    $email,
    $mailSubject,
    implode("\r\n", $bodyLines)
);

if (!$result['sent']) {
    error_log('[c-wald rostock/submit.php] mail() failed: ' . cwald_sanitize_for_log($result['error']));
}

cwald_mail_log(
    __DIR__ . '/waitlist.log',
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
        'mail_ok'  => $result['sent'],
        'mail_err' => $result['error'],
    ]
);

// A valid submission reached us — the lead is captured in waitlist.log
// regardless of the mail outcome, so record the event in either case.
cwald_telemetry_send('rostock_waitinglist', ['POST']);

if (!$result['sent']) {
    // The log file still has the entry, so the lead isn't lost — but tell the user.
    respond(false, 'Eintragung gespeichert, aber Mail-Versand fehlgeschlagen. Bitte schreiben Sie an hallo@c-wald.eu.', 500);
}

respond(true, 'Danke. Sie stehen auf der Liste.');
