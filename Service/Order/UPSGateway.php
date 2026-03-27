<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order\Order;
use App\ServiceInterface\Order\UPSGatewayInterface;

final class UPSGateway implements UPSGatewayInterface
{
    public function createShipment(Order $order, string $carrier): string
    {
        return 'UPS-'.strtoupper(bin2hex(random_bytes(4)));
    }

    public function updateShipmentStatus(string $trackingNumber, string $status): bool
    {
        return true;
    }

    public function getShipmentStatus(string $trackingNumber): ?string
    {
        return 'delivered';
    }
}
