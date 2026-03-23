<?php

declare(strict_types=1);

namespace App\Service\Shipment;

interface CarrierInterface
{
    public function createShipment(string $carrier, int $orderId): string;
}
