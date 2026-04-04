<?php

declare(strict_types=1);

namespace App\ServiceInterface\Http\Order;

interface AuditLoggerInterface
{
    public function log(array $payload): void;
}
