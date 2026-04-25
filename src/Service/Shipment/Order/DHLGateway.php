<?php

declare(strict_types=1);

namespace App\Service\Shipment\Order;

use App\Contract\Gateway\Order\OrderShipmentGatewayInterface;
use App\Entity\Order;
use App\ServiceInterface\Shipment\Order\DHLGatewayInterface;

final class DHLGateway implements DHLGatewayInterface, OrderShipmentGatewayInterface
{
    public function createShipment(Order $order, string $carrier): string
    {
        $seed = method_exists($order, 'getNumber')
            ? (string) $order->getNumber()
            : (string) ($order->getId() ?? spl_object_id($order));

        return $this->ship($seed, $carrier);
    }

    public function ship(string $orderId, string $carrierCode, array $context = []): string
    {
        return strtoupper($carrierCode).'-'.substr(hash('sha256', $orderId.$carrierCode.microtime()), 0, 12);
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
