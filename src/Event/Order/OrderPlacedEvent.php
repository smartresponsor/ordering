<?php

declare(strict_types=1);

namespace App\Event\Order;

final readonly class OrderPlacedEvent
{
    public function __construct(public int $orderId)
    {
    }
}
