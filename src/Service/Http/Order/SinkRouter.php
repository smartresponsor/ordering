<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use App\ServiceInterface\Http\Order\AuditSinkInterface;
use App\ServiceInterface\Http\Order\SinkRouterInterface;

final class SinkRouter implements SinkRouterInterface
{
    /** @param iterable<AuditSinkInterface> $sinks */
    public function __construct(private readonly iterable $sinks = [])
    {
    }

    public function route(array $payload): void
    {
        foreach ($this->sinks as $sink) {
            $sink->write($payload);
        }
    }
}
