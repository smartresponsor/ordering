<?php

declare(strict_types=1);

namespace App\Ordering\Model\Billing\Order;

final readonly class OrderTransaction
{
    public function __construct(
        private string $transactionId,
        private string $orderId,
        private string $amount,
        private string $currency,
        private string $provider,
        private string $status,
    ) {
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
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

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
