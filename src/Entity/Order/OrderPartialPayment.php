<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderPartialPayment
{
    public function __construct(
        private string $id,
        private string $orderId,
        private int $amountMinor,
        private string $currency,
        private string $paymentMethod,
    ) {}

    public function id(): string { return $this->id; }
    public function orderId(): string { return $this->orderId; }
}
