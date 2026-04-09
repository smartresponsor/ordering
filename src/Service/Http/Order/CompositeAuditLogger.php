<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use App\ServiceInterface\Http\Order\AuditLoggerInterface;

final readonly class CompositeAuditLogger implements AuditLoggerInterface
{
    public function __construct(private readonly SinkRouter $sinkRouter)
    {
    }

    public function log(array $payload): void
    {
        $this->sinkRouter->route($payload);
    }
}
