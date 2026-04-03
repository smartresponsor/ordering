<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\OrderShipment;

final readonly class OrderDeliveredEvent
{
    public function __construct(public OrderShipment $shipment)
    {
    }
}
