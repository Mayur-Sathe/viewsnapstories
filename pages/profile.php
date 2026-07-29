<?php
$page_title = 'Snapchat Profile - ' . e($username);
$page_desc = 'View ' . e($username) . '\'s public Snapchat profile, stories, and content anonymously. No login required.';
$og_title = '@' . e($username) . ' - Snapchat Profile';
$canonical = '/profile/' . e($username);
$page_keywords = e($username) . ', snapchat, view snapchat stories, snapchat story viewer';

$client = new SnapchatClient();
$profile = $client->fetchStories($username);
?>
<div class="container py-4">
    <?php if (!$profile['success']): ?>
    <div class="text-center py-5">
        <h3 class="text-danger mb-3">😕 <?= e($profile['error'] ?? 'Username not found') ?></h3>
        <p>Try a different username or <a href="/">search again</a>.</p>
    </div>
    <?php else: ?>
    <div>
        <div class="profile-header">
            <img src="<?= e($profile['avatar']) ?>" class="avatar" alt="Profile picture" onerror="this.src='/assets/img/snaplogo2.png'">
            <div>
                <h2 class="mb-1"><?= e($profile['display_name']) ?></h2>
                <p class="text-muted mb-1">@<?= e($profile['username']) ?></p>
                <?php if ($profile['bio']): ?><p class="mb-1"><?= e($profile['bio']) ?></p><?php endif; ?>
                <?php if ($profile['follower_count'] > 0): ?><p class="mb-0"><strong><?= number_format($profile['follower_count']) ?></strong> followers</p><?php endif; ?>
            </div>
        </div>

        <script type="application/ld+json">{
            "@context":"https://schema.org",
            "@type":"ProfilePage",
            "dateModified":"<?= date('Y-m-d') ?>",
            "mainEntity":{
                "@type":"Person",
                "name":<?= json_encode($profile['display_name']) ?>,
                "alternateName":<?= json_encode('@' . $profile['username']) ?>,
                "description":<?= json_encode($profile['bio'] ?? '') ?>,
                "image":<?= json_encode($profile['avatar']) ?>,
                "url":<?= json_encode(SITE_URL . '/profile/' . $profile['username']) ?>,
                "interactionStatistic":{
                    "@type":"InteractionCounter",
                    "interactionType":"https://schema.org/FollowAction",
                    "userInteractionCount":<?= (int)$profile['follower_count'] ?>
                }
            }
        }</script>

        <div class="d-flex flex-column flex-md-row gap-3 mb-4">
            <div class="position-relative flex-grow-1" style="max-width:400px">
                <input type="text" id="ps-input" class="shadow-sm form-control" placeholder="Search another username..."
                    onkeydown="if(event.key==='Enter'){goProfile(document.getElementById('ps-input').value)}">
                <button type="button" title="Paste" class="btn btn-sm btn-warning paste-btn"
                    onclick="navigator.clipboard.readText().then(t=>{document.getElementById('ps-input').value=t})">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path d="M64 0C28.7 0 0 28.7 0 64v320c0 35.3 28.7 64 64 64h112V224c0-61.9 50.1-112 112-112h64V64c0-35.3-28.7-64-64-64H64zM248 112H104c-13.3 0-24-10.7-24-24s10.7-24 24-24h144c13.3 0 24 10.7 24 24s-10.7 24-24 24zm40 48H192c-35.3 0-64 28.7-64 64v224c0 35.3 28.7 64 64 64h160c35.3 0 64-28.7 64-64V245.5c0-16.1-6.4-31.5-17.8-42.9L330.9 164.2c-11.4-11.4-26.8-17.8-42.9-17.8z"/></svg>
                </button>
            </div>
            <button class="btn btn-warning flex-shrink-0" onclick="goProfile(document.getElementById('ps-input').value)">View Story 👀</button>
        </div>

        <h3 class="mb-3">Stories & Spotlight</h3>
        <?php if (!empty($profile['stories'])): ?>
        <div class="story-grid">
            <?php foreach ($profile['stories'] as $story): ?>
            <div class="story-card">
                <div class="story-overlay">
                    <img src="<?= e($story['thumbnail_url'] ?: $profile['avatar']) ?>" alt="Story" loading="lazy" onerror="this.src='/assets/img/snaplogo2.png'">
                    <?php if ($story['media_type'] === 'video'): ?><span class="play-badge">▶</span><?php endif; ?>
                    <?php if (($story['type'] ?? '') === 'spotlight'): ?><span class="duration-badge">✨</span><?php endif; ?>
                </div>
                <?php if ($story['media_url']): ?>
                <div class="p-2">
                    <a href="/dl.php?url=<?= urlencode($story['media_url']) ?>" class="btn btn-sm btn-warning w-100">Download</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5 bg-light rounded-4">
            <p class="mb-0 text-muted">No active stories or spotlight right now for @<?= e($username) ?>.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>


