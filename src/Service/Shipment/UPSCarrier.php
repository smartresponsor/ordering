<?php

declare(strict_types=1);

namespace App\Ordering\Service\Shipment;

use App\Ordering\ServiceInterface\Shipment\CarrierInterface;

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
