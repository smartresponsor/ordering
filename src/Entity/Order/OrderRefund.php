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
#[ORM\Table(name: 'order_refund')]
class OrderRefund
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderRefund')]
    private Order $order;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPartial = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $refundedAt;

    public function __construct(Order $order, string $amount, string $currency, ?string $reason = null, bool $isPartial = true)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->order = $order;
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
        $this->reason = $reason;
        $this->isPartial = $isPartial;
        $this->refundedAt = new \DateTimeImmutable();
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
