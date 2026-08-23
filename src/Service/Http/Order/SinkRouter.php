<?php

declare(strict_types=1);

namespace App\Ordering\Service\Http\Order;

use App\Ordering\ServiceInterface\Http\Order\AuditSinkInterface;
use App\Ordering\ServiceInterface\Http\Order\SinkRouterInterface;

final readonly class SinkRouter implements SinkRouterInterface
{
    /** @param iterable<AuditSinkInterface> $sinks */
    public function __construct(private iterable $sinks = [])
    {
    }

    public function route(array $payload): void
    {
        foreach ($this->sinks as $sink) {
            $sink->write($payload);
        }
    }
}
