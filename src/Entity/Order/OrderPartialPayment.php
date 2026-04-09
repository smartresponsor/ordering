<?php

declare(strict_types=1);

namespace App\Entity\Order;

final readonly class OrderPartialPayment
{
    public function __construct(
        private readonly string $id,
        private readonly string $orderId,
        private readonly int $amountMinor,
        private readonly string $currency,
        private readonly string $paymentMethod,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }
}
