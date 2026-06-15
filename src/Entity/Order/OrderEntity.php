<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Contract\Domain\RecordsDomainEvents;
use App\Event\Domain\Order\OrderPaidEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\Event\Domain\Order\OrderShippedEvent;
use App\Lifecycle\OrderLifecyclePolicy;
use App\Repository\Order\OrderRepository;
use App\ValueObject\OrderStatus;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class OrderEntity implements RecordsDomainEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'guid', unique: true)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $number;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $grandTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $paidTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $refundedTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $subtotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $discountTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $taxTotal = '0.00';

    #[ORM\Column(type: 'string', length: 32)]
    private string $status = OrderStatus::Draft->value;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $customerId = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $vendorId = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $trackingCode = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    /** @var Collection<int, OrderPaymentEntity> */
    #[ORM\OneToMany(targetEntity: OrderPaymentEntity::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $payments;

    /** @var Collection<int, OrderRefundEntity> */
    #[ORM\OneToMany(targetEntity: OrderRefundEntity::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $refunds;

    /** @var Collection<int, OrderShipmentEntity> */
    #[ORM\OneToMany(targetEntity: OrderShipmentEntity::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $shipments;

    /** @var Collection<int, OrderItemEntity> */
    #[ORM\OneToMany(targetEntity: OrderItemEntity::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    /** @var Collection<int, OrderStatusHistoryEntity> */
    #[ORM\OneToMany(targetEntity: OrderStatusHistoryEntity::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $statusHistory;

    /** @var list<object> */
    private array $releasedEvents = [];

    public function __construct(mixed $arg1 = 'USD', mixed $arg2 = '0.00', ?string $arg3 = null, ?string $arg4 = null)
    {
        $now = new \DateTimeImmutable();
        $this->slug = Uuid::v7()->toRfc4122();
        $this->number = 'ORD-'.substr(str_replace('-', '', Uuid::v4()->toRfc4122()), 0, 12);
        $this->payments = new ArrayCollection();
        $this->refunds = new ArrayCollection();
        $this->shipments = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
        $this->createdAt = $now;
        $this->updatedAt = $now;

        if ($arg2 instanceof Money) {
            $this->vendorId = is_string($arg1) ? $arg1 : null;
            $this->currency = strtoupper((string) $arg2->getCurrency());
            $this->grandTotal = self::normalizeAmount($arg2->getAmount());
            $this->subtotal = $this->grandTotal;

            return;
        }

        if (is_string($arg1) && is_numeric($arg2) && is_string($arg3) && 3 === strlen($arg3)) {
            $this->number = $arg1;
            $this->currency = strtoupper($arg3);
            $this->customerId = $arg4;
            $this->grandTotal = self::normalizeAmount(self::normalizeLegacyAmount($arg2));
            $this->subtotal = $this->grandTotal;

            return;
        }

        $currency = is_string($arg1) ? $arg1 : 'USD';
        $grandTotal = $arg2;
        $this->currency = strtoupper($currency);
        $this->grandTotal = self::normalizeAmount(self::normalizeLegacyAmount($grandTotal));
        $this->subtotal = $this->grandTotal;
    }

    public static function create(string $currency = 'USD', string|int|float $grandTotal = '0.00'): self
    {
        return new self($currency, $grandTotal);
    }

    public function initAudit(): void
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
        $this->touch();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMarking(): string
    {
        return $this->status;
    }

    public function setMarking(string $marking): void
    {
        $this->status = $marking;
        $this->touch();
    }

    public function status(): string
    {
        return $this->status;
    }

    public function getGrandTotal(): string
    {
        return $this->grandTotal;
    }

    public function grandTotal(): string
    {
        return $this->grandTotal;
    }

    public function getTotal(): string
    {
        return $this->grandTotal;
    }

    public function getTotalAmount(): string
    {
        return $this->grandTotal;
    }

    public function getPaidTotal(): string
    {
        return $this->paidTotal;
    }

    public function paidTotal(): string
    {
        return $this->paidTotal;
    }

    public function getRefundedTotal(): string
    {
        return $this->refundedTotal;
    }

    public function refundedTotal(): string
    {
        return $this->refundedTotal;
    }

    public function getSubtotal(): string
    {
        return $this->subtotal;
    }

    public function getDiscountTotal(): string
    {
        return $this->discountTotal;
    }

    public function getTaxTotal(): string
    {
        return $this->taxTotal;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getVendorId(): ?string
    {
        return $this->vendorId;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function setStatus(OrderStatus|string $status): void
    {
        $newStatus = $status instanceof OrderStatus ? $status->value : strtolower((string) $status);
        $previousStatus = $this->status;
        OrderLifecyclePolicy::assertCanTransition($previousStatus, $newStatus);

        if ($previousStatus === $newStatus) {
            return;
        }

        $this->status = $newStatus;
        $this->statusHistory->add(new OrderStatusHistoryEntity($this, $previousStatus, $newStatus));
        $this->touch();
    }

    public function setCurrency(string|\Stringable $currency): void
    {
        $this->currency = strtoupper((string) $currency);
        $this->touch();
    }

    public function setGrandTotal(string|int|float $grandTotal): void
    {
        $this->grandTotal = self::normalizeAmount(self::normalizeLegacyAmount($grandTotal));
        $this->touch();
    }

    public function setTotal(string|int|float $grandTotal): void
    {
        $this->setGrandTotal($grandTotal);
    }

    public function setTotalAmount(string|int|float $grandTotal): void
    {
        $this->setGrandTotal($grandTotal);
    }

    public function setTaxAmount(string|int|float $taxTotal): void
    {
        $this->setTaxTotal($taxTotal);
    }

    public function setSubtotal(string|int|float $subtotal): void
    {
        $this->subtotal = self::normalizeAmount(self::normalizeLegacyAmount($subtotal));
        $this->touch();
    }

    public function setDiscountTotal(string|int|float $discountTotal): void
    {
        $this->discountTotal = self::normalizeAmount(self::normalizeLegacyAmount($discountTotal));
        $this->touch();
    }

    public function setTaxTotal(string|int|float $taxTotal): void
    {
        $this->taxTotal = self::normalizeAmount(self::normalizeLegacyAmount($taxTotal));
        $this->touch();
    }

    /** @return Collection<int, OrderPaymentEntity> */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /** @return Collection<int, OrderRefundEntity> */
    public function getRefunds(): Collection
    {
        return $this->refunds;
    }

    /** @return Collection<int, OrderShipmentEntity> */
    public function getShipments(): Collection
    {
        return $this->shipments;
    }

    /** @return Collection<int, OrderItemEntity> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /** @return Collection<int, OrderStatusHistoryEntity> */
    public function getStatusHistory(): Collection
    {
        return $this->statusHistory;
    }

    public function addItem(OrderItemEntity $item): void
    {
        if (!$this->items->contains($item)) {
            $item->setOrder($this);
            $this->items->add($item);
            $this->touch();
        }
    }

    public function applyPayment(string $amount, string $externalRef, bool $isPartial = true, string $gateway = 'stripe'): OrderPaymentEntity
    {
        $normalizedAmount = self::normalizeAmount($amount);
        $payment = new OrderPaymentEntity($this, $gateway, $normalizedAmount, $this->currency, $externalRef, $isPartial);
        $this->payments->add($payment);
        $this->paidTotal = bcadd($this->paidTotal, $normalizedAmount, 2);
        $this->setStatus(bccomp($this->paidTotal, $this->grandTotal, 2) >= 0 ? OrderStatus::Paid->value : 'partially_paid');
        $this->recordEvent(new OrderPaidEvent($this->slug, $normalizedAmount, $this->currency, $externalRef));
        $this->touch();

        return $payment;
    }

    public function refund(string $amount, ?string $reason = null, bool $isPartial = true): OrderRefundEntity
    {
        $normalizedAmount = self::normalizeAmount($amount);
        $refund = new OrderRefundEntity($this, $normalizedAmount, $this->currency, $reason, $isPartial);
        $this->refunds->add($refund);
        $this->refundedTotal = bcadd($this->refundedTotal, $normalizedAmount, 2);
        if (bccomp($this->refundedTotal, $this->paidTotal, 2) >= 0) {
            $this->setStatus(OrderStatus::Refunded->value);
        }
        $this->recordEvent(new OrderRefundedEvent($this, $normalizedAmount));
        $this->touch();

        return $refund;
    }

    public function ship(string $carrier, ?string $trackingCode = null, ?string $note = null): OrderShipmentEntity
    {
        $shipment = new OrderShipmentEntity($this, $carrier, $trackingCode, $note);
        $this->shipments->add($shipment);
        foreach ($this->items as $item) {
            $shipment->attachItem($item, $item->getQuantity());
        }
        $this->trackingCode = $trackingCode;
        $this->setStatus(OrderStatus::Shipped->value);
        $this->recordEvent(new OrderShippedEvent($this->slug));
        $this->touch();

        return $shipment;
    }

    public function shipItems(int $count, ?string $note = null): void
    {
        if ($count < 1) {
            throw new \DomainException('ShipmentEntity item count must be greater than zero.');
        }

        if (bccomp($this->paidTotal, $this->grandTotal, 2) < 0) {
            throw new \DomainException('Cannot ship items before the order is fully paid.');
        }

        $shipment = new OrderShipmentEntity($this, 'manual', null, $note);
        $this->shipments->add($shipment);

        $remaining = $count;
        foreach ($this->items as $item) {
            if ($remaining <= 0) {
                break;
            }

            $quantity = min($remaining, $item->getQuantity());
            if ($quantity > 0) {
                $shipment->attachItem($item, $quantity);
                $remaining -= $quantity;
            }
        }

        $this->setStatus('partially_shipped');
        $this->recordEvent(new OrderShippedEvent($this->slug));
        $this->touch();
    }

    public function markPaid(?string $amount = null): void
    {
        if (null !== $amount) {
            $this->paidTotal = bcadd($this->paidTotal, self::normalizeAmount($amount), 2);
        }
        $this->setStatus(OrderStatus::Paid->value);
        $this->touch();
    }

    public function markAsPaid(): void
    {
        $this->markPaid();
    }

    public function markRefunded(?string $amount = null): void
    {
        if (null !== $amount) {
            $this->refundedTotal = bcadd($this->refundedTotal, self::normalizeAmount($amount), 2);
        }
        $this->setStatus(OrderStatus::Refunded->value);
        $this->touch();
    }

    public function markAsShipped(): void
    {
        $this->setStatus(OrderStatus::Shipped->value);
        $this->touch();
    }

    public function markAsCompleted(): void
    {
        $this->setStatus(OrderStatus::Completed->value);
        $this->touch();
    }

    public function assignTracking(string $tracking): void
    {
        $this->trackingCode = $tracking;
        $this->touch();
    }

    public function applyPartialPayment(string $amount, ?string $externalRef = null, bool $isPartial = true, string $gateway = 'stripe'): OrderPaymentEntity
    {
        return $this->applyPayment($amount, $externalRef ?? ('PAY-'.$this->slug), $isPartial, $gateway);
    }

    public function refundPartial(string $amount, ?string $reason = null, bool $isPartial = true): void
    {
        if (bccomp($this->paidTotal, '0.00', 2) <= 0) {
            throw new \DomainException('Cannot refund before payment.');
        }

        $this->refund($amount, $reason, $isPartial);

        if (OrderStatus::Refunded->value !== $this->status) {
            $this->setStatus('partially_refunded');
        }
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->releasedEvents;
        $this->releasedEvents = [];

        return $events;
    }

    private function recordEvent(object $event): void
    {
        $this->releasedEvents[] = $event;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    private static function normalizeAmount(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    private static function normalizeLegacyAmount(mixed $amount): string|int|float
    {
        if ($amount instanceof Money) {
            return $amount->getAmount();
        }
        if (is_bool($amount)) {
            return '0.00';
        }
        if (is_int($amount) && $amount > 1000) {
            return number_format($amount / 100, 2, '.', '');
        }

        return is_scalar($amount) ? $amount : '0.00';
    }
}
