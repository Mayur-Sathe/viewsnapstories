<?php
require_once __DIR__ . '/../inc/bootstrap.php';

function normalizeUsername(string $raw): string {
    $u = trim($raw);
    if (preg_match('/snapchat\.com\/(?:add\/|@)([a-z0-9._-]+)/i', $u, $m)) {
        return strtolower($m[1]);
    }
    $u = preg_replace('/^@/', '', $u);
    $result = strtolower(preg_replace('/[^a-z0-9._-]/i', '', $u));
    return $result;
}

function getSnapchatClient(): SnapchatClient {
    return new SnapchatClient();
}

function handle_fetch_stories() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];
    $username = normalizeUsername($input['username'] ?? '');
    if (!validate_username($username)) {
        json_response(['success' => false, 'error' => 'Invalid username format'], 400);
    }
    $client = getSnapchatClient();
    $result = $client->fetchStories($username);
    if (!$result['success']) {
        json_response($result, 404);
    }
    json_response($result);
}

function handle_fetch_profile() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];
    $username = normalizeUsername($input['username'] ?? '');
    if (!validate_username($username)) {
        json_response(['success' => false, 'error' => 'Invalid username format'], 400);
    }
    $client = getSnapchatClient();
    $result = $client->fetchProfile($username);
    if (!$result['success']) {
        json_response($result, 404);
    }
    json_response($result);
}

function handle_search_users() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];
    $query = trim($input['query'] ?? '');
    if (preg_match('/snapchat\.com\/(?:add\/|@)([a-z0-9._-]+)/i', $query, $m)) {
        $query = $m[1];
    }
    if (strlen($query) < 2) {
        json_response(['success' => false, 'error' => 'Query must be at least 2 characters'], 400);
    }
    $client = getSnapchatClient();
    $result = $client->searchUsers($query);
    json_response($result);
}

function handle_download() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];
    $mediaUrl = trim($input['media_url'] ?? '');
    if (!filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
        json_response(['success' => false, 'error' => 'Invalid media URL'], 400);
    }
    $client = getSnapchatClient();
    $data = $client->downloadMedia($mediaUrl);
    if (!$data) {
        json_response(['success' => false, 'error' => 'Could not download media'], 500);
    }
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="snapchat_story_' . time() . '.mp4"');
    header('Content-Length: ' . strlen($data));
    echo $data;
    exit;
}

function handle_followers() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];
    $username = normalizeUsername($input['username'] ?? '');
    if (!validate_username($username)) {
        json_response(['success' => false, 'error' => 'Invalid username format'], 400);
    }
    $client = getSnapchatClient();
    $result = $client->getFollowerCount($username);
    if (!$result['success']) {
        json_response($result, 404);
    }
    json_response($result);
}
