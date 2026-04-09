<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final readonly class OrderShipmentCommand
{
    public function __construct(public readonly string $orderId, public readonly string $carrier) {
    }
}
