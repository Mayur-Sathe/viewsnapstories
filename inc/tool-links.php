<?php
$postsFile = __DIR__ . '/../data/posts.json';
$blogPosts = [];
if (file_exists($postsFile)) {
    $posts = json_decode(file_get_contents($postsFile), true) ?? [];
    $blogPosts = array_slice($posts, 0, 3);
}
?>
<section class="bg-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <h3 class="h5 mb-3">🔧 More Tools</h3>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/">📖 Snapchat Story Viewer</a></li>
                    <li class="mb-2"><a href="/view-profile">👤 Profile Viewer</a></li>
                    <li class="mb-2"><a href="/snapchat-story-downloader">⬇️ Story Downloader</a></li>
                    <li class="mb-2"><a href="/snapchat-spotlight-downloader">✨ Spotlight Downloader</a></li>
                    <li class="mb-2"><a href="/snapchat-video-downloader">🎬 Video Downloader</a></li>
                    <li class="mb-2"><a href="/username-finder">🔍 Username Finder</a></li>
                    <li class="mb-2"><a href="/snapchat-followers-count">📊 Followers Count</a></li>
                    <li class="mb-2"><a href="/is-snapchat-down">⚠️ Is Snapchat Down?</a></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3 class="h5 mb-3">📝 Latest Guides</h3>
                <?php if (empty($blogPosts)): ?>
                <p class="text-muted mb-0">Check back soon for guides.</p>
                <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($blogPosts as $bp): ?>
                    <li class="mb-2"><a href="/blog/<?= e($bp['slug']) ?>"><?= e($bp['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/blog/" class="btn btn-sm btn-outline-warning mt-2">View All Guides →</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
