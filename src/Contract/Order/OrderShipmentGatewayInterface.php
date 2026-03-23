<?php

declare(strict_types=1);

namespace App\Contract\Order;

interface OrderShipmentGatewayInterface
{
    public function ship(string $orderId, string $carrierCode, array $context = []): string;
}
