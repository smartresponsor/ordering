<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_payment_allocation')]
class OrderPaymentAllocationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderPaymentEntity::class, inversedBy: 'allocations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderPaymentEntity $payment;

    #[ORM\ManyToOne(targetEntity: OrderItemEntity::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?OrderItemEntity $orderItem;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    public function __construct(OrderPaymentEntity $payment, ?OrderItemEntity $orderItem, string|int|float $amount)
    {
        $this->payment = $payment;
        $this->orderItem = $orderItem;
        $this->amount = number_format((float) $amount, 2, '.', '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): OrderPaymentEntity
    {
        return $this->payment;
    }

    public function getOrderItem(): ?OrderItemEntity
    {
        return $this->orderItem;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
