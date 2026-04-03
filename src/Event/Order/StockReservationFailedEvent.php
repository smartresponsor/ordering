<?php

declare(strict_types=1);

namespace App\Event\Order;

use App\Entity\Order;

final readonly class StockReservationFailedEvent
{
    public function __construct(public Order $order, public string $reason)
    {
    }
}
