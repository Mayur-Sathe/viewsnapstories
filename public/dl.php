<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc/bootstrap.php';
require_once __DIR__ . '/../lib/Http.php';

$url = $_GET['url'] ?? '';
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('Invalid URL');
}

$data = Http::get($url);
if ($data === null) {
    http_response_code(502);
    die('Download failed');
}

$head = substr($data, 0, 12);
if (strpos($head, "\xff\xd8\xff") === 0) {
    $mime = 'image/jpeg'; $ext = 'jpg';
} elseif (strpos($head, "\x89PNG") === 0) {
    $mime = 'image/png'; $ext = 'png';
} elseif (strpos($head, 'GIF8') === 0) {
    $mime = 'image/gif'; $ext = 'gif';
} elseif (strpos($head, "\x1a\x45\xdf\xa3") === 0) {
    $mime = 'video/webm'; $ext = 'webm';
} elseif (strpos($head, "\x00\x00\x00") === 0) {
    $mime = 'video/mp4'; $ext = 'mp4';
} elseif (strlen($data) > 12 && strpos(substr($head, 0, 4), 'RIFF') === 0 && strpos(substr($head, 8, 4), 'WEBP') === 0) {
    $mime = 'image/webp'; $ext = 'webp';
} else {
    $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    $mime = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp', 'mp4' => 'video/mp4', 'webm' => 'video/webm'][strtolower($ext)] ?? 'application/octet-stream';
    if (!$ext) $ext = 'bin';
}

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="snapchat_' . time() . '.' . $ext);
header('Content-Length: ' . strlen($data));
echo $data;
