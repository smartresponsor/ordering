<?php

declare(strict_types=1);

namespace App\Entity\Order\Billing;

final class OrderPaymentIntent
{
    private string $status = 'pending';

    public function __construct(
        private readonly string $orderId,
        private readonly string $amount,
        private readonly string $currency,
        private readonly string $gateway,
        private ?string $intentId = null,
    ) {
        if (null === $this->intentId) {
            $this->intentId = 'pi_'.bin2hex(random_bytes(8));
        }
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

    public function getGateway(): string
    {
        return $this->gateway;
    }

    public function getIntentId(): string
    {
        return (string) $this->intentId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function markConfirmed(): void
    {
        $this->status = 'confirmed';
    }

    public function markFailed(): void
    {
        $this->status = 'failed';
    }
}
