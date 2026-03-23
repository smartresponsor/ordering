<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order\Http;

interface AuditLoggerInterface
{
    public function log(array $payload): void;
}
