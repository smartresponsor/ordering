<?php

declare(strict_types=1);

namespace App\Service\Shipment\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\ServiceInterface\Shipment\Order\UPSGatewayInterface;

final class UPSGateway implements UPSGatewayInterface
{
    public function createShipment(OrderEntity $order, string $carrier): string
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
