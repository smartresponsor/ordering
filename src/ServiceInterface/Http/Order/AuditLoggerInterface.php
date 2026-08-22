<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Http\Order;

interface AuditLoggerInterface
{
    public function log(array $payload): void;
}
