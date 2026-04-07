<?php

declare(strict_types=1);

namespace App\Entity\Order\Billing;

final class OrderTransaction
{
    private string $status = 'pending';

    public function __construct(
        private string $orderId,
        private string $amount,
        private string $currency,
        private string $reference,
    ) {
    }

    public function confirm(): void
    {
        $this->status = 'confirmed';
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}
