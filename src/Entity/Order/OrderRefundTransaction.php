<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Symfony\Component\Uid\Uuid;

final readonly class OrderRefundTransaction
{
    private string $id;
    private string $orderId;
    private string $refundId;
    private ?string $paymentRef;
    private string $amount;
    private string $currency;
    private ?string $reason;

    public function __construct(
        string $arg1,
        string|int|float $arg2,
        ?string $arg3 = null,
        ?string $arg4 = null,
        ?int $arg5 = null,
        ?string $arg6 = null,
    ) {
        if (null !== $arg5) {
            $this->id = $arg1;
            $this->orderId = (string) $arg2;
            $this->refundId = $arg3 ?? '';
            $this->paymentRef = $arg4;
            $this->reason = null;
            $this->amount = number_format($arg5 / 100, 2, '.', '');
            $this->currency = strtoupper($arg6 ?? 'USD');

            return;
        }

        $this->id = Uuid::v7()->toRfc4122();
        $this->orderId = $arg1;
        $this->amount = number_format((float) $arg2, 2, '.', '');
        $this->refundId = $arg3 ?? '';
        $this->paymentRef = null;
        $this->reason = $arg4;
        $this->currency = 'USD';
    }

    public function id(): string
    {
        return $this->id;
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
