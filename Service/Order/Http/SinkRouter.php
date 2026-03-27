<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use App\ServiceInterface\Order\Http\AuditSinkInterface;
use App\ServiceInterface\Order\Http\SinkRouterInterface;

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
