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
#[ORM\Table(name: 'order_taxation_audit')]
#[ORM\Index(columns: ['order_id', 'created_at'], name: 'idx_tax_audit_order_created')]
class OrderTaxationAudit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $subtotal;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $taxTotal;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $discountTotal;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $finalTotal;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(Order $order, string $subtotal, string $taxTotal, string $discountTotal, string $finalTotal)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->order = $order;
        $this->subtotal = $subtotal;
        $this->taxTotal = $taxTotal;
        $this->discountTotal = $discountTotal;
        $this->finalTotal = $finalTotal;
        $this->createdAt = new \DateTimeImmutable('now');
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
