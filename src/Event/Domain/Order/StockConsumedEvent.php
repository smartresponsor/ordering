<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class StockConsumedEvent
{
    public function __construct(public InventoryReservation $reservation)
    {
    }
}
