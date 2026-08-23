<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class StockReservedEvent
{
    public function __construct(public InventoryReservation $reservation)
    {
    }
}
