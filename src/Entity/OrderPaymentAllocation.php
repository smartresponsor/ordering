<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_payment_allocation')]
class OrderPaymentAllocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderPayment::class, inversedBy: 'allocations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderPayment $payment;

    #[ORM\ManyToOne(targetEntity: OrderItem::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?OrderItem $orderItem;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    public function __construct(OrderPayment $payment, ?OrderItem $orderItem, string|int|float $amount)
    {
        $this->payment = $payment;
        $this->orderItem = $orderItem;
        $this->amount = number_format((float) $amount, 2, '.', '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): OrderPayment
    {
        return $this->payment;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
