<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use App\ServiceInterface\Order\DHLGatewayInterface;

final class DHLGateway implements DHLGatewayInterface
{
    public function createShipment(Order $order, string $carrier): string
    {
        return 'DHL-'.strtoupper(bin2hex(random_bytes(4)));
    }

    public function updateShipmentStatus(string $trackingNumber, string $status): bool
    {
        return true;
    }

    public function getShipmentStatus(string $trackingNumber): ?string
    {
        return 'in_transit';
    }
}
