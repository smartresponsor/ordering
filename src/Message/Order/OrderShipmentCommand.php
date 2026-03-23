<?php

declare(strict_types=1);

namespace App\Message\Order;

final class OrderShipmentCommand
{
    public function __construct(public string $orderId, public string $carrier)
    {
    }
}
