<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderShipmentView
{
    public function __construct(
        private string $orderId,
        private string $carrier,
        private string $tracking,
        private string $status,
        private ?\DateTimeImmutable $deliveredAt = null,
    ) {}

    public function id(): string { return $this->orderId; }
    public function update(string $carrier, string $tracking, string $status, ?\DateTimeImmutable $deliveredAt = null): void
    {
        $this->carrier = $carrier;
        $this->tracking = $tracking;
        $this->status = $status;
        $this->deliveredAt = $deliveredAt;
    }
}
