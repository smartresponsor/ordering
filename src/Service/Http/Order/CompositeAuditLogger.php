<?php

declare(strict_types=1);

namespace App\Ordering\Service\Http\Order;

use App\Ordering\ServiceInterface\Http\Order\AuditLoggerInterface;

final readonly class CompositeAuditLogger implements AuditLoggerInterface
{
    public function __construct(private SinkRouter $sinkRouter)
    {
    }

    public function log(array $payload): void
    {
        $this->sinkRouter->route($payload);
    }
}
