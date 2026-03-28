<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_refund')]
class OrderRefund
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'refunds')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $reason;

    #[ORM\Column(type: 'boolean')]
    private bool $isPartial;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $refundedAt;

    public function __construct(Order $order, string $amount, string $currency, ?string $reason = null, bool $isPartial = true)
    {
        $this->order = $order;
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
        $this->reason = $reason;
        $this->isPartial = $isPartial;
        $this->refundedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getRefundedAt(): \DateTimeImmutable
    {
        return $this->refundedAt;
    }
}
