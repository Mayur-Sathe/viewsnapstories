<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Serve static files directly for PHP built-in server
$filePath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    return false;
}

$method = $_SERVER['REQUEST_METHOD'];

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$GLOBALS['_INPUT'] = [];
if ($method === 'POST') {
    $input = $_POST['csrf_token'] ?? '';
    if ($input === '' && ($raw = file_get_contents('php://input'))) {
        $data = json_decode($raw, true);
        $GLOBALS['_INPUT'] = $data ?: [];
        $input = $data['csrf_token'] ?? '';
    }
    if ($input !== $_SESSION['csrf_token']) {
        http_response_code(419);
        die(json_encode(['success' => false, 'error' => 'CSRF token mismatch']));
    }
}

function render($page, $data = []) {
    global $TOOLS;
    extract($data);
    if (!isset($breadcrumbs)) {
        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
        if ($uri !== '/' && $uri !== '/sitemap') {
            $label = $TOOLS[$uri]['short'] ?? ucwords(str_replace(['-', '_'], ' ', trim(basename($uri), '/')));
            $breadcrumbs = [['Home', '/'], [$label]];
        }
    }
    require __DIR__ . '/../inc/header.php';
    require __DIR__ . "/../pages/$page.php";
    require __DIR__ . '/../inc/footer.php';
    exit;
}

function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// API routes
if (strpos($uri, '/x/') === 0) {
    header('Content-Type: application/json');
    if ($method === 'POST' && $uri === '/x/snapchat/fetch-stories') {
        require __DIR__ . '/../x/snapchat.php';
        handle_fetch_stories();
    }
    if ($method === 'POST' && $uri === '/x/snapchat/fetch-profile') {
        require __DIR__ . '/../x/snapchat.php';
        handle_fetch_profile();
    }
    if ($method === 'POST' && $uri === '/x/snapchat/search-users') {
        require __DIR__ . '/../x/snapchat.php';
        handle_search_users();
    }
    if ($method === 'POST' && $uri === '/x/snapchat/download') {
        require __DIR__ . '/../x/snapchat.php';
        handle_download();
    }
    if ($method === 'POST' && $uri === '/x/snapchat/followers') {
        require __DIR__ . '/../x/snapchat.php';
        handle_followers();
    }
    if ($uri === '/x/status/snapchat') {
        require __DIR__ . '/../x/status.php';
        handle_status_check();
    }
    if ($method === 'POST' && $uri === '/x/contact') {
        require __DIR__ . '/../x/contact.php';
        handle_contact();
    }
    json_response(['success' => false, 'error' => 'Not found'], 404);
}

// Handle direct form POST (fallback if Alpine.js fails to load)
if ($method === 'POST' && $uri === '/') {
    $input = $_POST['username'] ?? '';
    // Extract username from URL like the JS does
    $u = trim(strtolower($input));
    if (preg_match('/snapchat\.com\/(?:add\/|@|stories\/|t\/)?([a-z0-9._-]+)/i', $u, $m)) {
        $u = $m[1];
    } else {
        $u = preg_replace('/^@/', '', $u);
        $u = preg_replace('/[^a-z0-9._-]/i', '', $u);
    }
    if ($u !== '') {
        header('Location: /profile/' . $u);
        exit;
    }
    render('home');
}

// Sitemap (raw XML, no header/footer)
if ($uri === '/sitemap') {
    require __DIR__ . '/../pages/sitemap.php';
    exit;
}

// Page routes
$routes = [
    '/'                         => 'home',
    '/view-profile'             => 'view-profile',
    '/snapchat-story-downloader' => 'story-downloader',
    '/snapchat-spotlight-downloader' => 'spotlight-downloader',
    '/snapchat-video-downloader'    => 'video-downloader',
    '/username-finder'             => 'username-finder',
    '/snapchat-followers-count'    => 'followers-count',
    '/is-snapchat-down'            => 'is-snapchat-down',
    '/about'                       => 'about',
    '/how-it-works'                => 'how-it-works',
    '/faq'                         => 'faq',
    '/contact'                     => 'contact',
    '/privacy-policy'              => 'privacy-policy',
    '/terms-of-service'            => 'terms-of-service',
];

if (isset($routes[$uri])) {
    if ($uri === '/') {
        render('home', [
            'page_title' => 'Snapchat Story Viewer & Downloader',
            'page_desc' => 'Use our anonymous Snapchat viewer to watch stories without login or app. View profiles privately, download videos and Spotlight content.',
            'og_title' => 'Snapchat Story Viewer — View & Download Stories Anonymously',
            'og_desc' => 'Use our anonymous Snapchat viewer and snap viewer to watch public Snapchat stories anonymously without login or app. Fast, secure Snapchat viewer online.',
            'canonical' => '/',
        ]);
    } else {
        render($routes[$uri]);
    }
}

if ($uri === '/blog' || $uri === '/blog/') {
    render('blog/index', [
        'page_title' => 'Blog - Snapchat Tips & Guides',
        'page_desc' => 'Read the latest guides and tutorials about Snapchat features, tips, and how to use our tools.',
        'og_title' => 'Blog - View Snap Stories',
        'canonical' => '/blog/',
    ]);
}

if (preg_match('#^/blog/(.+)$#', $uri, $m)) {
    $slug = $m[1];
    $postsFile = __DIR__ . '/../data/posts.json';
    $posts = file_exists($postsFile) ? (json_decode(file_get_contents($postsFile), true) ?? []) : [];
    $post = null;
    foreach ($posts as $p) {
        if ($p['slug'] === $slug) { $post = $p; break; }
    }
    if (!$post) {
        http_response_code(404);
        render('404', ['page_title' => 'Post Not Found', 'page_desc' => 'The blog post was not found.', 'page_noindex' => true]);
    }
    $renderData = [
        'slug' => $slug,
        'post' => $post,
        'page_title' => $post['title'] . ' - Blog',
        'page_desc' => $post['excerpt'],
        'og_title' => $post['title'],
        'canonical' => '/blog/' . $post['slug'],
    ];
    render('blog/single', $renderData);
}

if (preg_match('#^/profile/(.+)$#', $uri, $m)) {
    $username = trim(urldecode($m[1]));
    $username = preg_replace('/[^a-z0-9._-]/i', '', $username);
    if ($username === '') {
        header('Location: /');
        exit;
    }
    render('profile', ['username' => $username, 'breadcrumbs' => [['Home', '/'], ['@' . e($username)]]]);
}

http_response_code(404);
render('404', ['page_noindex' => true]);
