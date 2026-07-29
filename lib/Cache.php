<?php
class Cache {
    private string $dir;
    private int $ttl;

    public function __construct(string $dir = null, int $ttl = 300) {
        $this->dir = $dir ?: __DIR__ . '/../data/cache';
        $this->ttl = $ttl;
        if (!is_dir($this->dir)) mkdir($this->dir, 0755, true);
    }

    public function get(string $key): mixed {
        $file = $this->filepath($key);
        if (!file_exists($file)) return null;
        $data = json_decode(file_get_contents($file), true);
        if (!$data || $data['expires_at'] < time()) {
            @unlink($file);
            return null;
        }
        return $data['data'];
    }

    public function set(string $key, mixed $data): void {
        $file = $this->filepath($key);
        $payload = [
            'data' => $data,
            'created_at' => time(),
            'expires_at' => time() + $this->ttl,
        ];
        file_put_contents($file, json_encode($payload), LOCK_EX);
    }

    public function clear(string $key = null): void {
        if ($key) {
            @unlink($this->filepath($key));
        } else {
            array_map('unlink', glob($this->dir . '/*.json'));
        }
    }

    private function filepath(string $key): string {
        return $this->dir . '/' . md5($key) . '.json';
    }
}
