<?php

declare(strict_types=1);

namespace App\Integration\Shipment;

use App\Contract\Order\OrderShipmentGatewayInterface;

final class DHLCarrier implements OrderShipmentGatewayInterface, CarrierInterface
{
    public function __construct(private ?string $apiKey = null)
    {
    }

    public function name(): string
    {
        return 'DHL';
    }

    public function fetchStatus(string $trackingNumber): ?CarrierStatusUpdate
    {
        $delivered = str_contains(strtolower($trackingNumber), 'delivered');

        return new CarrierStatusUpdate(
            $delivered ? 'delivered' : 'in_transit',
            $delivered ? new \DateTimeImmutable() : null,
        );
    }

    public function ship(string $orderId, string $carrierCode, array $context = []): string
    {
        return 'JD'.strtoupper(substr(hash('sha1', $orderId.$carrierCode.($this->apiKey ?? 'dhl')), 0, 16));
    }
}
