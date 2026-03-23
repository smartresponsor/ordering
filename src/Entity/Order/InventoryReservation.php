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
#[ORM\Table(name: 'inventory_reservation')]
#[ORM\UniqueConstraint(name: 'uniq_reservation_key', columns: ['reservation_key'])]
class InventoryReservation
{
    public const STATE_RESERVED = 'reserved';
    public const STATE_RELEASED = 'released';
    public const STATE_CONSUMED = 'consumed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(name: 'reservation_key', length: 80)]
    private string $reservationKey;

    #[ORM\Column(type: 'json')]
    private array $lines;

    #[ORM\Column(length: 16)]
    private string $state = self::STATE_RESERVED;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(Order $order, string $reservationKey, array $lines)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->order = $order;
        $this->reservationKey = $reservationKey;
        $this->lines = $lines;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getReservationKey(): string
    {
        return $this->reservationKey;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function markReleased(): void
    {
        $this->state = self::STATE_RELEASED;
    }

    public function markConsumed(): void
    {
        $this->state = self::STATE_CONSUMED;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getOrder(): Order
    {
        return $this->order;
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
