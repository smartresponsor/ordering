<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'order_refund_transaction')]
class OrderRefundTransactionEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'guid', unique: true)]
    private string $slug;

    #[ORM\Column(length: 64)]
    private string $orderId;

    #[ORM\Column(length: 64)]
    private string $refundId;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $paymentRef;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reason;

    public function __construct(string $arg1, string|int|float $arg2, ?string $arg3 = null, ?string $arg4 = null, ?int $arg5 = null, ?string $arg6 = null)
    {
        if (null !== $arg5) {
            $this->slug = $arg1;
            $this->orderId = (string) $arg2;
            $this->refundId = $arg3 ?? '';
            $this->paymentRef = $arg4;
            $this->reason = null;
            $this->amount = number_format($arg5 / 100, 2, '.', '');
            $this->currency = strtoupper($arg6 ?? 'USD');

            return;
        }

        $this->slug = Uuid::v7()->toRfc4122();
        $this->orderId = $arg1;
        $this->amount = number_format((float) $arg2, 2, '.', '');
        $this->refundId = $arg3 ?? '';
        $this->paymentRef = null;
        $this->reason = $arg4;
        $this->currency = 'USD';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->slug;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function refundId(): string
    {
        return $this->refundId;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }
}
