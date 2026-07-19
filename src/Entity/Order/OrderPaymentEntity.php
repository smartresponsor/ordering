<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_payment')]
class OrderPaymentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderEntity::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?OrderEntity $order = null;

    #[ORM\Column(length: 32)]
    private string $gateway = 'manual';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount = '0.00';

    #[ORM\Column(length: 3)]
    private string $currency = 'USD';

    #[ORM\Column(length: 64, unique: true, nullable: true)]
    private ?string $externalRef = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPartial = true;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status = 'captured';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $refundedAmount = '0.00';

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $capturedAt;

    /** @var Collection<int, OrderPaymentAllocationEntity> */
    #[ORM\OneToMany(targetEntity: OrderPaymentAllocationEntity::class, mappedBy: 'payment', cascade: ['persist'], orphanRemoval: true)]
    private Collection $allocations;

    public function __construct(?OrderEntity $order = null, string $gateway = 'manual', string|int|float $amount = '0.00', string $currency = 'USD', ?string $externalRef = null, bool $isPartial = true)
    {
        $this->order = $order;
        $this->gateway = $gateway;
        $this->amount = self::normalizeAmount($amount);
        $this->currency = strtoupper($currency);
        $this->externalRef = $externalRef;
        $this->isPartial = $isPartial;
        $this->capturedAt = new \DateTimeImmutable();
        $this->allocations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?OrderEntity
    {
        return $this->order;
    }

    public function setOrder(OrderEntity $order): void
    {
        $this->order = $order;
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string|int|float $amount): void
    {
        $this->amount = self::normalizeAmount($amount);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCapturedAt(): \DateTimeImmutable
    {
        return $this->capturedAt;
    }

    public function getReference(): ?string
    {
        return $this->externalRef;
    }

    public function setReference(string $reference): void
    {
        $this->externalRef = $reference;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPartial(): bool
    {
        return $this->isPartial;
    }

    public function setStatus(string $status): void
    {
        $this->status = strtolower($status);
    }

    public function markPaid(): void
    {
        $this->status = 'paid';
    }

    public function getRefundedAmount(): string
    {
        return $this->refundedAmount;
    }

    public function addRefundedAmount(string|int|float $amount): void
    {
        $this->refundedAmount = bcadd($this->refundedAmount, self::normalizeAmount($amount), 2);
    }

    /** @return Collection<int, OrderPaymentAllocationEntity> */
    public function getAllocations(): Collection
    {
        return $this->allocations;
    }

    public function allocateToItem(?OrderItemEntity $orderItem, string|int|float $amount): OrderPaymentAllocationEntity
    {
        $allocation = new OrderPaymentAllocationEntity($this, $orderItem, $amount);
        $this->allocations->add($allocation);

        return $allocation;
    }

    private static function normalizeAmount(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
