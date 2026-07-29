<?php
class Logger {
    private string $logDir;

    public function __construct(string $logDir = null) {
        $this->logDir = $logDir ?: __DIR__ . '/../logs';
        if (!is_dir($this->logDir)) mkdir($this->logDir, 0755, true);
    }

    public function error(string $message, array $context = []): void {
        $this->write('ERROR', $message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->write('WARNING', $message, $context);
    }

    public function info(string $message, array $context = []): void {
        $this->write('INFO', $message, $context);
    }

    public function api(string $endpoint, string $status, array $context = []): void {
        $this->write('API', "[$endpoint] $status", $context, 'api.log');
    }

    private function write(string $level, string $message, array $context = [], string $file = 'error.log'): void {
        $line = '[' . date('Y-m-d H:i:s') . '] [' . $level . '] ' . $message;
        if (!empty($context)) $line .= ' ' . json_encode($context);
        $line .= PHP_EOL;
        file_put_contents($this->logDir . '/' . $file, $line, FILE_APPEND | LOCK_EX);
    }
}
