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
#[ORM\Table(name: 'order_dispute')]
#[ORM\Index(columns: ['order_id', 'status'], name: 'idx_dispute_order_status')]
class OrderDispute
{
    public const STATUS_OPEN = 'open';
    public const STATUS_INVESTIGATING = 'investigating';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CHARGEBACK_PENDING = 'chargeback_pending';
    public const STATUS_CHARGEBACK_ISSUED = 'chargeback_issued';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $externalId = null;

    #[ORM\Column(length: 24)]
    private string $type; // inquiry|chargeback

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $reason = null; // fraud|duplicate|not_received|...

    #[ORM\Column(length: 32)]
    private string $status = self::STATUS_OPEN;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $openedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $resolvedAt = null;

    public function __construct(Order $order, string $type, ?string $reason, ?string $externalId = null)
    {
        $this->order = $order;
        $this->type = $type;
        $this->reason = $reason;
        $this->externalId = $externalId;
        $this->openedAt = new \DateTimeImmutable('now');
        $this->status = self::STATUS_OPEN;
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

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function markResolved(): void
    {
        $this->status = self::STATUS_RESOLVED;
        $this->resolvedAt = new \DateTimeImmutable('now');
    }
}
