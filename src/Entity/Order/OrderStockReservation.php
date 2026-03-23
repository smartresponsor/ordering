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
#[ORM\Table(name: 'order_stock_reservation')]
#[ORM\Index(columns: ['order_id'])]
#[ORM\Index(columns: ['status'])]
class OrderStockReservation
{
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_RELEASED = 'released';
    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(name: 'order_id', type: 'guid')]
    private string $orderId;

    #[ORM\Column(name: 'sku', length: 64)]
    private string $sku;

    #[ORM\Column(name: 'quantity', type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'string', length: 16)]
    private string $status = self::STATUS_RESERVED;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(string $orderId, string $sku, int $quantity)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->orderId = $orderId;
        $this->sku = $sku;
        $this->quantity = $quantity;
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

    public function sku(): string
    {
        return $this->sku;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function markReleased(): void
    {
        $this->status = self::STATUS_RELEASED;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS_FAILED;
        $this->updatedAt = new \DateTimeImmutable();
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
