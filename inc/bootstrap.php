<?php
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../lib/' . $class . '.php';
    if (file_exists($path)) require_once $path;
});

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function get_page_title($page_title = null) {
    if ($page_title) {
        return e($page_title) . ' - ' . SITE_NAME;
    }
    return SITE_NAME . ' – View & Download Stories Anonymously';
}

function get_page_description($desc = null) {
    return e($desc ?: 'Watch public Snapchat stories anonymously without login or app. View profiles privately and use our tools to download stories, videos and Spotlight content.');
}

function get_canonical($path = '/') {
    return SITE_URL . rtrim($path, '/');
}

function current_page($path) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    return $uri === $path ? 'active' : '';
}

function get_theme_class() {
    return isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'dark-theme' : '';
}

function breadcrumb_schema(array $items, string $uri = ''): string {
    $uri = $uri ?: parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $list = [];
    $i = 1;
    foreach ($items as $item) {
        $list[] = '{"@type":"ListItem","position":' . $i++ . ',"name":"' . addslashes($item[0]) . '","item":"' . SITE_URL . ($item[1] ?? $uri) . '"}';
    }
    return '<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[' . implode(',', $list) . ']}</script>';
}

function rate_limit_check() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $file = __DIR__ . '/../data/cache/ratelimit_' . md5($ip) . '.json';
    $now = time();
    $window = 60;
    $max = RATE_LIMIT;
    $data = ['count' => 0, 'reset_at' => $now + $window];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if ($data['reset_at'] < $now) {
            $data = ['count' => 0, 'reset_at' => $now + $window];
        }
    }
    $data['count']++;
    file_put_contents($file, json_encode($data), LOCK_EX);
    if ($data['count'] > $max) {
        http_response_code(429);
        json_response(['success' => false, 'error' => 'Too many requests', 'retry_after' => $data['reset_at'] - $now], 429);
    }
}

function validate_username($username) {
    $u = trim(strtolower($username));
    if (strlen($u) < 2 || strlen($u) > 30) return false;
    return preg_match('/^[a-z0-9._-]+$/', $u);
}

function log_error($msg, $context = []) {
    $log = '[' . date('Y-m-d H:i:s') . '] ' . $msg . ' ' . json_encode($context) . PHP_EOL;
    file_put_contents(__DIR__ . '/../logs/error.log', $log, FILE_APPEND | LOCK_EX);
}
