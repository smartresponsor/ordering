<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\InventoryReservation;

final readonly class StockConsumedEvent
{
    public function __construct(public InventoryReservation $reservation)
    {
    }
}
