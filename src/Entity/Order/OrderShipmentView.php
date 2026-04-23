<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderShipmentView
{
    public function __construct(
        private readonly string $orderId,
        private string $carrier,
        private string $tracking,
        private string $status,
        private ?\DateTimeImmutable $deliveredAt = null,
    ) {
    }

    public function id(): string
    {
        return $this->orderId;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function carrier(): string
    {
        return $this->carrier;
    }

    public function tracking(): string
    {
        return $this->tracking;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function deliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function update(string $carrier, string $tracking, string $status, ?\DateTimeImmutable $deliveredAt = null): void
    {
        $this->carrier = $carrier;
        $this->tracking = $tracking;
        $this->status = strtolower($status);
        $this->deliveredAt = $deliveredAt;
    }
}
