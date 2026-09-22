<?php
/**
 * Konfigurasi Global Sistem
 * Sistem Informasi Presensi Perkuliahan
 */

define('APP_NAME',       'Sistem Presensi');
define('APP_FULL_NAME',  'Sistem Informasi Presensi');
define('FAKULTAS_NAME',  'Fakultas Teknik');

// BASE_URL can be configured by environment, or deduced from the current request.
// Untuk virtual host Laragon: gunakan nama host seperti sistem_presensi.test dan set APP_URL jika perlu.
// Untuk memaksa HTTPS di server produksi, set FORCE_HTTPS=1.
$defaultBaseUrl = 'http://localhost/sistem_presensi';
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath  = trim($scriptDir, '/');
$autoBaseUrl = $protocol . '://' . $host . ($basePath !== '' ? '/' . $basePath : '');

define('BASE_URL', rtrim(getenv('APP_URL') ?: ($autoBaseUrl ?: $defaultBaseUrl), '/'));
define('ROOT_PATH',      dirname(__DIR__));
define('UPLOAD_PATH',    ROOT_PATH . '/uploads/foto_profil/');
define('UPLOAD_URL',     BASE_URL . '/uploads/foto_profil/');
define('ASSET_URL',      BASE_URL . '/assets');
define('SESSION_NAME',   'presensi_sess');
define('FORCE_HTTPS',    getenv('FORCE_HTTPS') === '1');
define('SESSION_SECURE', FORCE_HTTPS || ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443));
define('SESSION_SAMESITE','Lax');
define('ROWS_PER_PAGE',  10);
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024);
define('DEFAULT_TOLERANSI', 15); // menit

date_default_timezone_set('Asia/Jakarta');
ini_set('display_errors', 1);
error_reporting(E_ALL);
