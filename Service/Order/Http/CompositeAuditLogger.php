<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use App\ServiceInterface\Order\Http\AuditLoggerInterface;

final class CompositeAuditLogger implements AuditLoggerInterface
{
    public function __construct(private readonly SinkRouter $sinkRouter)
    {
    }

    public function log(array $payload): void
    {
        $this->sinkRouter->route($payload);
    }
}
