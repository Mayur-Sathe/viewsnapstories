<?php
require_once __DIR__ . '/../inc/bootstrap.php';

function handle_status_check() {
    $cache = new Cache(__DIR__ . '/../data/cache', 60);
    $cached = $cache->get('snapchat_status');
    if ($cached) {
        json_response($cached);
    }

    $start = microtime(true);
    $resp = Http::get('https://www.snapchat.com');
    $responseTime = round((microtime(true) - $start) * 1000);

    $httpCode = $resp !== null ? 200 : 0;

    $status = 'offline';
    if ($httpCode >= 200 && $httpCode < 400) {
        $status = 'online';
    } elseif ($httpCode >= 400 && $httpCode < 500) {
        $status = 'online';
    } else {
        $status = 'degraded';
    }

    $result = [
        'success' => true,
        'status' => $status,
        'http_code' => $httpCode,
        'response_time_ms' => $responseTime,
        'checked_at' => gmdate('Y-m-d\TH:i:s\Z'),
        'downdetector_url' => 'https://downdetector.com/status/snapchat/',
    ];

    $cache->set('snapchat_status', $result);
    json_response($result);
}
