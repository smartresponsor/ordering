<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_payment')]
class OrderPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Order $order = null;

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

    public function __construct(?Order $order = null, string $gateway = 'manual', string|int|float $amount = '0.00', string $currency = 'USD', ?string $externalRef = null, bool $isPartial = true)
    {
        $this->order = $order;
        $this->gateway = $gateway;
        $this->amount = self::normalizeAmount($amount);
        $this->currency = strtoupper($currency);
        $this->externalRef = $externalRef;
        $this->isPartial = $isPartial;
        $this->capturedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
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

    private static function normalizeAmount(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
