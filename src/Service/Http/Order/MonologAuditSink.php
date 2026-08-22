<?php

declare(strict_types=1);

namespace App\Ordering\Service\Http\Order;

use App\Ordering\ServiceInterface\Http\Order\AuditSinkInterface;

final readonly class MonologAuditSink implements AuditSinkInterface
{
    public function write(array $payload): void
    {
    }
}
