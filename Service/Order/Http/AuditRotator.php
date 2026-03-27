<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use App\ServiceInterface\Order\Http\AuditRotateInterface;

final class AuditRotator implements AuditRotateInterface
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function rotate(): void
    {
        $path = rtrim($this->projectDir, '/\\').'/var/log/order-audit.ndjson';
        if (!is_file($path)) {
            return;
        }

        $rotated = rtrim($this->projectDir, '/\\').'/var/log/order-audit-'.date('Ymd-His').'.ndjson';
        @rename($path, $rotated);
    }
}
