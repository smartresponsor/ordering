<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment_view')]
class OrderShipmentViewEntity
{
    #[ORM\Id]
    #[ORM\Column(length: 64)]
    private readonly string $orderId;

    #[ORM\Column(length: 64)]
    private string $carrier;

    #[ORM\Column(length: 128)]
    private string $tracking;

    #[ORM\Column(length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    public function __construct(string $orderId, string $carrier, string $tracking, string $status, ?\DateTimeImmutable $deliveredAt = null)
    {
        $this->orderId = $orderId;
        $this->carrier = $carrier;
        $this->tracking = $tracking;
        $this->status = $status;
        $this->deliveredAt = $deliveredAt;
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
