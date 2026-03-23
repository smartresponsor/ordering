<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'order_refund_tx')]
#[ORM\Index(columns: ['order_id'])]
class OrderRefundTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(name: 'order_id', type: 'guid')]
    private string $orderId;

    #[ORM\Column(type: 'decimal', precision: 18, scale: 2)]
    private string $amount;

    #[ORM\Column(type: 'string', length: 64)]
    private string $refundId;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $orderId, string $amount, string $refundId, ?string $reason = null)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->refundId = $refundId;
        $this->reason = $reason;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): int
    {
        return $this->id;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function refundId(): string
    {
        return $this->refundId;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;

        return $this;
    }
}
