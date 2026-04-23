<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderReturnPolicy
{
    private ?\DateTimeImmutable $deliveredAt = null;

    public function __construct(private readonly OrderShipment $shipment, private readonly int $daysWindow = 14)
    {
        $this->setDeliveredRecalculate();
    }

    public function setDeliveredRecalculate(): void
    {
        $this->deliveredAt = $this->shipment->getDeliveredAt() ?? new \DateTimeImmutable();
    }

    public function getAutoExpireDate(): ?\DateTimeImmutable
    {
        return $this->deliveredAt?->modify(sprintf('+%d days', $this->daysWindow));
    }
}
