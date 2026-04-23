<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderShippedEvent
{
    public function __construct(public string $orderId)
    {
    }
}
