<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order\Order;
use App\ServiceInterface\Order\OrderShipmentGatewayInterfaceInterface;

interface OrderShipmentGatewayInterface extends OrderShipmentGatewayInterfaceInterface
{
    /**
     * Create shipment for given Order and return tracking number.
     */
    public function createShipment(Order $order, string $carrier): string;

    /**
     * Update shipment status by tracking number.
     */
    public function updateShipmentStatus(string $trackingNumber, string $status): bool;

    /**
     * Get current shipment status by tracking number.
     */
    public function getShipmentStatus(string $trackingNumber): ?string;
}
