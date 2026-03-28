<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment')]
class OrderShipment
{
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

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $shippedAt;

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

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function getShippedAt(): \DateTimeImmutable
    {
        return $this->shippedAt;
    }
}
