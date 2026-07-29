<?php
require_once __DIR__ . '/Http.php';

class SnapchatClient {
    private Cache $cache;

    public function __construct() {
        $this->cache = new Cache();
    }

    public function fetchStories(string $username): array {
        $cacheKey = 'stories_' . strtolower($username);
        $cached = $this->cache->get($cacheKey);
        if ($cached) return $cached;

        $data = $this->scrapeAll($username);
        if (!$data) {
            $data = $this->resolveTLink($username);
        }
        if (!$data) {
            return ['success' => false, 'error' => 'Username not found'];
        }

        $stories = $this->extractStories($data['__NEXT_DATA__']);
        $spotlight = $this->extractSpotlight($data['__NEXT_DATA__']);
        $stories = array_merge($stories, $spotlight);

        $profile = $data['profile'];
        $result = [
            'success' => true,
            'username' => $profile['username'],
            'display_name' => $profile['display_name'],
            'avatar' => $profile['avatar'],
            'bio' => $profile['bio'],
            'follower_count' => $profile['follower_count'],
            'stories' => $stories,
        ];

        $this->cache->set($cacheKey, $result);
        return $result;
    }

    public function fetchProfile(string $username): array {
        $cacheKey = 'profile_' . strtolower($username);
        $cached = $this->cache->get($cacheKey);
        if ($cached) return $cached;

        $data = $this->scrapeAll($username);
        if (!$data) {
            $data = $this->resolveTLink($username);
        }
        if (!$data) {
            return ['success' => false, 'error' => 'Username not found'];
        }

        $result = array_merge(['success' => true, 'is_public' => true], $data['profile']);
        $this->cache->set($cacheKey, $result);
        return $result;
    }

    public function searchUsers(string $query): array {
        return ['success' => false, 'error' => 'Search not available', 'results' => []];
    }

    public function getFollowerCount(string $username): array {
        $data = $this->fetchProfile($username);
        if (!$data['success']) return $data;
        return [
            'success' => true,
            'username' => $data['username'],
            'follower_count' => $data['follower_count'],
        ];
    }

    public function downloadMedia(string $url): ?string {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return null;
        return Http::get($url);
    }

    private function scrapeAll(string $username): ?array {
        $html = Http::get('https://www.snapchat.com/@' . urlencode($username));
        if (!$html) return null;
        if (!preg_match('/__NEXT_DATA__[^>]*>(.*?)<\/script>/s', $html, $m)) return null;
        $json = json_decode($m[1], true);
        if (!$json) return null;
        $pp = $json['props']['pageProps'] ?? null;
        if (!$pp) return null;

        $profile = $pp['userProfile']['publicProfileInfo'] ?? null;
        if (!$profile) return null;

        return [
            '__NEXT_DATA__' => $json,
            'profile' => [
                'username' => $profile['username'] ?? $username,
                'display_name' => $profile['title'] ?? $username,
                'avatar' => $profile['profilePictureUrl'] ?? '',
                'bio' => $profile['bio'] ?? '',
                'follower_count' => (int)($profile['subscriberCount'] ?? 0),
            ],
        ];
    }

    private function resolveTLink(string $snapId): ?array {
        $html = Http::get('https://www.snapchat.com/t/' . urlencode($snapId));
        if (!$html) return null;
        if (preg_match('/property="og:url"\s+content="([^"]+)"/i', $html, $m)) {
            $profileUrl = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
            if (preg_match('/snapchat\.com\/@([a-z0-9._-]+)/i', $profileUrl, $p)) {
                return $this->scrapeAll($p[1]);
            }
        }
        return null;
    }

    private function extractStories(array $nextData): array {
        $story = $nextData['props']['pageProps']['story'] ?? null;
        if (!$story || empty($story['snapList'])) return [];

        $stories = [];
        foreach ($story['snapList'] as $snap) {
            $mediaType = $snap['snapMediaType'] ?? 0;
            $urls = $snap['snapUrls'] ?? [];
            $stories[] = [
                'id' => $snap['snapId']['value'] ?? ('snap_' . count($stories)),
                'media_type' => $mediaType === 1 ? 'video' : 'image',
                'media_url' => $urls['mediaUrl'] ?? '',
                'thumbnail_url' => $urls['mediaPreviewUrl']['value'] ?? $urls['mediaUrl'] ?? '',
                'timestamp' => (int)($snap['timestampInSec']['value'] ?? 0),
                'duration' => 0,
                'type' => 'story',
            ];
        }
        return $stories;
    }

    private function extractSpotlight(array $nextData): array {
        $highlights = $nextData['props']['pageProps']['spotlightHighlights'] ?? [];
        if (empty($highlights)) return [];

        $items = [];
        $meta = $nextData['props']['pageProps']['spotlightStoryMetadata'] ?? [];
        foreach ($highlights as $i => $hl) {
            foreach ($hl['snapList'] ?? [] as $snap) {
                $mediaType = $snap['snapMediaType'] ?? 0;
                $urls = $snap['snapUrls'] ?? [];
                $m = $meta[$i] ?? [];
                $items[] = [
                    'id' => $snap['snapId']['value'] ?? ('spot_' . count($items)),
                    'media_type' => $mediaType === 1 ? 'video' : 'image',
                    'media_url' => $urls['mediaUrl'] ?? '',
                    'thumbnail_url' => $urls['mediaPreviewUrl']['value'] ?? $urls['mediaUrl'] ?? '',
                    'timestamp' => (int)($snap['timestampInSec']['value'] ?? 0),
                    'duration' => 0,
                    'type' => 'spotlight',
                    'description' => $m['description'] ?? '',
                    'hashtags' => $m['hashtags'] ?? [],
                ];
            }
        }
        return $items;
    }
}
