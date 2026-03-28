<?php

declare(strict_types=1);

namespace App\Service\Shipment;

use App\ServiceInterface\Shipment\CarrierInterface;

final class UPSCarrier implements CarrierInterface
{
    /**
     * @throws \Exception
     */
    public function createShipment(string $carrier, string $orderId): string
    {
        return '1Z'.strtoupper(bin2hex(random_bytes(6)));
    }
}
