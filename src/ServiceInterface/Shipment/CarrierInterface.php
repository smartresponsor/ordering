<?php

declare(strict_types=1);

namespace App\ServiceInterface\Shipment;

interface CarrierInterface
{
    public function createShipment(string $carrier, string $orderId): string;
}
