<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_shipment_item')]
class OrderShipmentItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderShipmentEntity::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderShipmentEntity $shipment;

    #[ORM\ManyToOne(targetEntity: OrderItemEntity::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?OrderItemEntity $orderItem;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    public function __construct(OrderShipmentEntity $shipment, ?OrderItemEntity $orderItem, int $quantity = 1)
    {
        $this->shipment = $shipment;
        $this->orderItem = $orderItem;
        $this->quantity = max(1, $quantity);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShipment(): OrderShipmentEntity
    {
        return $this->shipment;
    }

    public function getOrderItem(): ?OrderItemEntity
    {
        return $this->orderItem;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
