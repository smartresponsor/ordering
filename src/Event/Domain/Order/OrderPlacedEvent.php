<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderPlacedEvent
{
    public function __construct(public readonly int $orderId) {
    }
}
