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
#[ORM\Table(name: 'order_shipment_item')]
class OrderShipmentItem
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderShipment')]
    private Order $order;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $note = null;

    public function __construct(Order $order, int $quantity, ?string $note = null)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->order = $order;
        $this->quantity = $quantity;
        $this->note = $note;
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
