<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Http\Order;

interface AuditSinkInterface
{
    public function write(array $payload): void;
}
