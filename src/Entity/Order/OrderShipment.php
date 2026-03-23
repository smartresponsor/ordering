<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment')]
#[ORM\Index(columns: ['order_id', 'status'], name: 'idx_shipment_order_status')]
class OrderShipment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(length: 24)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $carrier = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $trackingCode = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryProofUrl = null;

    public function __construct(Order $order, ?string $carrier = null, ?string $tracking = null)
    {
        $this->order = $order;
        $this->carrier = $carrier;
        $this->trackingCode = $tracking;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function markInTransit(): void
    {
        $this->status = self::STATUS_IN_TRANSIT;
    }

    public function markDelivered(\DateTimeInterface $at): void
    {
        $this->status = self::STATUS_DELIVERED;
        $this->deliveredAt = \DateTimeImmutable::createFromInterface($at);
    }

    public function markCompleted(): void
    {
        $this->status = self::STATUS_COMPLETED;
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS_FAILED;
    }
}
