<?php
header('Content-Type: application/xml; charset=utf-8');

// Load blog posts for sitemap
$postsFile = __DIR__ . '/../data/posts.json';
$blogPosts = [];
if (file_exists($postsFile)) {
    $blogPosts = json_decode(file_get_contents($postsFile), true) ?? [];
}

$pages = [
    ['loc' => '/', 'priority' => '1.00'],
    ['loc' => '/view-profile', 'priority' => '0.80'],
    ['loc' => '/snapchat-story-downloader', 'priority' => '0.80'],
    ['loc' => '/snapchat-spotlight-downloader', 'priority' => '0.80'],
    ['loc' => '/snapchat-video-downloader', 'priority' => '0.80'],
    ['loc' => '/username-finder', 'priority' => '0.80'],
    ['loc' => '/snapchat-followers-count', 'priority' => '0.80'],
    ['loc' => '/is-snapchat-down', 'priority' => '0.80'],
    ['loc' => '/about', 'priority' => '0.60'],
    ['loc' => '/how-it-works', 'priority' => '0.60'],
    ['loc' => '/faq', 'priority' => '0.60'],
    ['loc' => '/contact', 'priority' => '0.40'],
    ['loc' => '/blog/', 'priority' => '0.80'],
    ['loc' => '/privacy-policy', 'priority' => '0.30'],
    ['loc' => '/terms-of-service', 'priority' => '0.30'],
];

foreach ($blogPosts as $bp) {
    $pages[] = ['loc' => '/blog/' . $bp['slug'], 'priority' => '0.70', 'lastmod' => date('Y-m-d', $bp['updated_at'] ?? $bp['created_at'])];
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $p): ?>
<url>
    <loc><?= SITE_URL . $p['loc'] ?></loc>
    <lastmod><?= $p['lastmod'] ?? date('Y-m-d') ?></lastmod>
    <priority><?= $p['priority'] ?></priority>
</url>
<?php endforeach; ?>
</urlset>
<?php exit; ?>
