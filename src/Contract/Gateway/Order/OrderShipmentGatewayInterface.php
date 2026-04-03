<?php

declare(strict_types=1);

namespace App\Contract\Gateway\Order;

interface OrderShipmentGatewayInterface
{
    /** @param array<string, mixed> $context */
    public function ship(string $orderId, string $carrierCode, array $context = []): string;
}
