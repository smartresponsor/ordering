<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

use App\Entity\Order\OrderEntity;

/**
 * ShipmentEntity gateway contract for Ordering service layer.
 */
interface OrderShipmentGatewayInterface
{
    /**
     * Create shipment for given OrderEntity and return tracking number.
     */
    public function createShipment(OrderEntity $OrderEntity, string $carrier): string;

    /**
     * Update shipment status by tracking number.
     */
    public function updateShipmentStatus(string $trackingNumber, string $status): bool;

    /**
     * Get current shipment status by tracking number.
     */
    public function getShipmentStatus(string $trackingNumber): ?string;
}
