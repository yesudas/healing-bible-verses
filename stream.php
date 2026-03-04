<?php
/**
 * Stream audio with HTTP Range support for seeking.
 *
 * Security: Same rules as download.php – only .mp3 inside /languages/.
 */

ini_set('display_errors', '0');
error_reporting(0);

$lang = isset($_GET['lang']) ? $_GET['lang'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';

if ($lang === '' || $file === '') {
    http_response_code(400);
    exit('Bad request.');
}

if (preg_match('/\.\./', $lang) || preg_match('/\.\./', $file)) {
    http_response_code(403);
    exit('Forbidden.');
}

if (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), 'mp3') !== 0) {
    http_response_code(403);
    exit('Forbidden – only MP3 files are allowed.');
}

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

if (strcasecmp(pathinfo($filePath, PATHINFO_EXTENSION), 'mp3') !== 0) {
    http_response_code(403);
    exit('Forbidden.');
}

// ── Serve with Range support ──────────────────────────────────────────────────
$fileSize = filesize($filePath);
$mimeType = 'audio/mpeg';

if (ob_get_level()) {
    ob_end_clean();
}

// Parse Range header
$start = 0;
$end   = $fileSize - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
    // Range: bytes=start-end
    if (preg_match('/bytes=(\d*)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)) {
        $rangeStart = $matches[1];
        $rangeEnd   = $matches[2];

        if ($rangeStart !== '') {
            $start = intval($rangeStart);
        }
        if ($rangeEnd !== '') {
            $end = intval($rangeEnd);
        }

        // Validate
        if ($start > $end || $start >= $fileSize || $end >= $fileSize) {
            http_response_code(416); // Range Not Satisfiable
            header("Content-Range: bytes */$fileSize");
            exit;
        }

        $length = $end - $start + 1;

        http_response_code(206); // Partial Content
        header("Content-Range: bytes $start-$end/$fileSize");
        header("Content-Length: $length");
    } else {
        // Malformed range header – serve full file
        header("Content-Length: $fileSize");
    }
} else {
    header("Content-Length: $fileSize");
}

header("Content-Type: $mimeType");
header("Accept-Ranges: bytes");
header("Cache-Control: public, max-age=86400");
header("Content-Disposition: inline");

$handle = fopen($filePath, 'rb');
if ($handle === false) {
    http_response_code(500);
    exit('Server error.');
}

fseek($handle, $start);

$remaining = ($end - $start) + 1;
$bufferSize = 8192;

while ($remaining > 0 && !feof($handle)) {
    $read = min($bufferSize, $remaining);
    echo fread($handle, $read);
    $remaining -= $read;
    flush();
}

fclose($handle);
exit;
