<?php
define('SITE_URL', 'https://viewsnapstories.onrender.com');
define('SITE_NAME', 'View Snapchat Stories');
define('SITE_TAGLINE', 'View Snapchat stories anonymously');
define('AD_CLIENT', 'ca-pub-9531077997187448');
define('GA_MEASUREMENT_ID', 'G-MMT1FWBMR3');
define('KO_FI_URL', 'https://ko-fi.com/A0A21UNUTF');
define('CONTACT_EMAIL', 'contact@viewsnapstories.com');
define('CACHE_TTL', 300);
define('RATE_LIMIT', 30);
define('DEBUG_MODE', false);
define('SITE_VERSION', '1.0.0');

$LANGUAGES = [
    'en' => ['label' => 'English (EN)', 'flag' => 'united-states.png', 'url' => ''],
    'de' => ['label' => 'German (DE)', 'flag' => 'germany.png', 'url' => 'de'],
    'es' => ['label' => 'Spanish (ES)', 'flag' => 'spain.png', 'url' => 'es'],
    'fr' => ['label' => 'French (FR)', 'flag' => 'france.png', 'url' => 'fr'],
    'nl' => ['label' => 'Dutch (NL)', 'flag' => 'dutch.png', 'url' => 'nl'],
    'ar' => ['label' => 'Arabic (AR)', 'flag' => 'saudi-arabia.png', 'url' => 'ar'],
    'hi' => ['label' => 'हिंदी (HI)', 'flag' => 'india.png', 'url' => 'hi'],
];

$TOOLS = [
    '/' => ['name' => 'Snapchat Story Viewer', 'short' => 'Story Viewer'],
    '/view-profile' => ['name' => 'Snapchat Profile Viewer', 'short' => 'Profile Viewer'],
    '/snapchat-story-downloader' => ['name' => 'Snapchat Story Downloader', 'short' => 'Story Downloader'],
    '/snapchat-spotlight-downloader' => ['name' => 'Snapchat Spotlight Downloader', 'short' => 'Spotlight Downloader'],
    '/snapchat-video-downloader' => ['name' => 'Snapchat Video Downloader', 'short' => 'Video Downloader'],
    '/username-finder' => ['name' => 'Snapchat Username Finder', 'short' => 'Username Finder'],
    '/snapchat-followers-count' => ['name' => 'Snapchat Followers Count', 'short' => 'Followers Count'],
    '/is-snapchat-down' => ['name' => 'Is Snapchat down?', 'short' => 'Is Snapchat Down'],
];
