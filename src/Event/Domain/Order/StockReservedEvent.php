<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\InventoryReservation;

final readonly class StockReservedEvent
{
    public function __construct(public readonly InventoryReservation $reservation) {
    }
}
