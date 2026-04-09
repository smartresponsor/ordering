<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment')]
class OrderShipment
{
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_IN_TRANSIT = 'in_transit';
    public const string STATUS_DELIVERED = 'delivered';
    public const string STATUS_COMPLETED = 'completed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'shipments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(length: 24)]
    private string $carrier;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $trackingCode;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note;

    #[ORM\Column(length: 24)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $shippedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    public function __construct(Order $order, string $carrier, ?string $trackingCode = null, ?string $note = null)
    {
        $this->order = $order;
        $this->carrier = $carrier;
        $this->trackingCode = $trackingCode;
        $this->note = $note;
        $this->shippedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function setTrackingCode(string $trackingCode): void
    {
        $this->trackingCode = $trackingCode;
    }

    public function getShippedAt(): \DateTimeImmutable
    {
        return $this->shippedAt;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function markInTransit(): void
    {
        $this->status = self::STATUS_IN_TRANSIT;
    }

    public function markDelivered(?\DateTimeInterface $at = null): void
    {
        $this->status = self::STATUS_DELIVERED;
        $this->deliveredAt = $at ? \DateTimeImmutable::createFromInterface($at) : new \DateTimeImmutable();
    }

    public function markCompleted(): void
    {
        $this->status = self::STATUS_COMPLETED;
    }

    public function markShipped(): void
    {
        $this->markInTransit();
    }
}
