<?php
declare(strict_types=1);

/**
 * Template for telemetry-config.php.
 *
 * Copy this file to lib/telemetry-config.php (which is gitignored) and fill
 * in the live values. The production copy on the Hetzner webspace must be
 * placed there once via SFTP — the GitHub Actions rsync deploy explicitly
 * excludes lib/telemetry-config.php so it never gets overwritten or deleted.
 *
 *   cp lib/telemetry-config.example.php lib/telemetry-config.php
 *   $EDITOR lib/telemetry-config.php
 *
 * The values:
 *   - CWALD_TELEMETRY_ENABLED  — master switch (false = no outbound calls).
 *   - CWALD_TELEMETRY_ENDPOINT — full URL of the log API.
 *   - CWALD_TELEMETRY_API_KEY  — value of the X-Api-Key header.
 *   - CWALD_TELEMETRY_DEBUG    — true = log every send to the PHP error log.
 *
 * No personal data is sent — see lib/telemetry.php for the fixed payload
 * schema ({tool, tool_version, instance, event}).
 */

if (!defined('CWALD_TELEMETRY_ENABLED'))  define('CWALD_TELEMETRY_ENABLED',  true);
if (!defined('CWALD_TELEMETRY_ENDPOINT')) define('CWALD_TELEMETRY_ENDPOINT', 'https://log.broetzens.de/api/log');
if (!defined('CWALD_TELEMETRY_API_KEY'))  define('CWALD_TELEMETRY_API_KEY',  'REPLACE_WITH_REAL_KEY');
if (!defined('CWALD_TELEMETRY_DEBUG'))    define('CWALD_TELEMETRY_DEBUG',    false);
