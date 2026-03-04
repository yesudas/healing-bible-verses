<?php
/**
 * Secure file download for Healing Bible Verses.
 *
 * Security measures:
 *  1. Only files inside the /languages directory tree are served.
 *  2. Directory-traversal attempts (../ etc.) are blocked via realpath validation.
 *  3. Only .mp3 files are allowed.
 *  4. Input is validated and sanitised before any filesystem access.
 *  5. PHP error display is suppressed so internal paths are never leaked.
 */

// Suppress errors in output
ini_set('display_errors', '0');
error_reporting(0);

// ── Input ──────────────────────────────────────────────────────────────────────
$lang = isset($_GET['lang']) ? $_GET['lang'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';

if ($lang === '' || $file === '') {
    http_response_code(400);
    exit('Bad request.');
}

// ── Block obvious traversal patterns before touching the filesystem ──────────
if (preg_match('/\.\./', $lang) || preg_match('/\.\./', $file)) {
    http_response_code(403);
    exit('Forbidden.');
}

// File must end with .mp3 (case-insensitive)
if (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), 'mp3') !== 0) {
    http_response_code(403);
    exit('Forbidden – only MP3 downloads are allowed.');
}

// ── Resolve and validate the real path ────────────────────────────────────────
$baseDir  = realpath(__DIR__ . '/languages');
$filePath = realpath($baseDir . '/' . $lang . '/' . $file);

if (
    $baseDir === false ||
    $filePath === false ||
    strpos($filePath, $baseDir . DIRECTORY_SEPARATOR) !== 0 ||
    !is_file($filePath)
) {
    http_response_code(404);
    exit('File not found.');
}

// Double-check the extension after realpath resolved symlinks
if (strcasecmp(pathinfo($filePath, PATHINFO_EXTENSION), 'mp3') !== 0) {
    http_response_code(403);
    exit('Forbidden.');
}

// ── Serve the file ────────────────────────────────────────────────────────────
$fileSize = filesize($filePath);
$fileName = basename($filePath);

// Clean output buffers to prevent memory issues with large files
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: audio/mpeg');
header('Content-Disposition: attachment; filename="' . rawurlencode($fileName) . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Stream in chunks to avoid PHP memory limits on large files
$handle = fopen($filePath, 'rb');
if ($handle === false) {
    http_response_code(500);
    exit('Server error.');
}

while (!feof($handle)) {
    echo fread($handle, 8192);
    flush();
}

fclose($handle);
exit;
