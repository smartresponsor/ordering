<?php

declare(strict_types=1);

namespace App\Integration\Shipment;

interface CarrierInterface
{
    public function name(): string;

    public function fetchStatus(string $trackingNumber): ?CarrierStatusUpdate;
}
