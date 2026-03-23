<?php

declare(strict_types=1);

namespace App\Integration\Shipment;

final readonly class CarrierStatusUpdate
{
    public function __construct(
        public string $status,
        public ?\DateTimeImmutable $deliveredAt = null,
    ) {
    }
}
