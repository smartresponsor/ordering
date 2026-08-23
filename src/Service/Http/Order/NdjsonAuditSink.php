<?php

declare(strict_types=1);

namespace App\Ordering\Service\Http\Order;

use App\Ordering\ServiceInterface\Http\Order\AuditSinkInterface;

final readonly class NdjsonAuditSink implements AuditSinkInterface
{
    private string $path;

    public function __construct(private string $projectDir)
    {
        $this->path = rtrim($this->projectDir, '/\\').'/var/log/order-audit.ndjson';
    }

    public function write(array $payload): void
    {
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if (false === $json) {
            return;
        }

        $dir = \dirname($this->path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents($this->path, $json.PHP_EOL, FILE_APPEND);
    }
}
