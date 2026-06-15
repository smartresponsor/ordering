<?php

declare(strict_types=1);

namespace App\Model\Billing\Order;

final readonly class OrderPaymentIntent
{
    public function __construct(
        private string $orderId,
        private string $amount,
        private string $currency,
        private string $provider,
        private string $intentId,
    ) {
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

    public function getIntentId(): string
    {
        return $this->intentId;
    }
}
