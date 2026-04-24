<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment_item')]
class OrderShipmentItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderShipment::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderShipment $shipment;

    #[ORM\ManyToOne(targetEntity: OrderItem::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?OrderItem $orderItem;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    public function __construct(OrderShipment $shipment, ?OrderItem $orderItem, int $quantity = 1)
    {
        $this->shipment = $shipment;
        $this->orderItem = $orderItem;
        $this->quantity = max(1, $quantity);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShipment(): OrderShipment
    {
        return $this->shipment;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
