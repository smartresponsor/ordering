<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderPlacedEvent
{
    public function __construct(public string $orderId)
    {
    }
}
