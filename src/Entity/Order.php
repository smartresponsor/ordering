<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $grandTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $paidTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $refundedTotal = '0.00';

    #[ORM\Column(type: 'string', length: 32)]
    private string $status = OrderStatus::Draft->value;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    /** @var Collection<int, OrderPayment> */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderPayment::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $payments;

    /** @var Collection<int, OrderRefund> */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderRefund::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $refunds;

    /** @var Collection<int, OrderShipment> */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderShipment::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $shipments;

    public function __construct(string $currency = 'USD', string $grandTotal = '0.00')
    {
        $now = new \DateTimeImmutable();

        $this->id = Uuid::v7()->toRfc4122();
        $this->currency = strtoupper($currency);
        $this->grandTotal = self::normalizeAmount($grandTotal);
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->payments = new ArrayCollection();
        $this->refunds = new ArrayCollection();
        $this->shipments = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->id;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @return Collection<int, OrderPayment> */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /** @return Collection<int, OrderRefund> */
    public function getRefunds(): Collection
    {
        return $this->refunds;
    }

    /** @return Collection<int, OrderShipment> */
    public function getShipments(): Collection
    {
        return $this->shipments;
    }

    public function applyPayment(string $amount, string $externalRef, bool $isPartial = true, string $gateway = 'stripe'): OrderPayment
    {
        $normalizedAmount = self::normalizeAmount($amount);
        $payment = new OrderPayment($this, $gateway, $normalizedAmount, $this->currency, $externalRef, $isPartial);
        $this->payments->add($payment);
        $this->paidTotal = bcadd($this->paidTotal, $normalizedAmount, 2);
        $this->status = bccomp($this->paidTotal, $this->grandTotal, 2) >= 0
            ? OrderStatus::Paid->value
            : OrderStatus::Placed->value;
        $this->touch();

        return $payment;
    }

    public function refund(string $amount, ?string $reason = null, bool $isPartial = true): OrderRefund
    {
        $normalizedAmount = self::normalizeAmount($amount);
        $refund = new OrderRefund($this, $normalizedAmount, $this->currency, $reason, $isPartial);
        $this->refunds->add($refund);
        $this->refundedTotal = bcadd($this->refundedTotal, $normalizedAmount, 2);
        $this->status = bccomp($this->refundedTotal, $this->paidTotal, 2) >= 0
            ? OrderStatus::Refunded->value
            : $this->status;
        $this->touch();

        return $refund;
    }

    public function ship(string $carrier, ?string $trackingCode = null, ?string $note = null): OrderShipment
    {
        $shipment = new OrderShipment($this, $carrier, $trackingCode, $note);
        $this->shipments->add($shipment);
        $this->status = OrderStatus::Shipped->value;
        $this->touch();

        return $shipment;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    private static function normalizeAmount(string $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
