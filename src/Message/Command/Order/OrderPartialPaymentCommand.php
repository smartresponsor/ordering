<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final readonly class OrderPartialPaymentCommand
{
    public function __construct(
        public string $orderId,
        public int $amountMinor,
        public string $currency,
        public string $paymentMethod,
        public ?string $idempotencyKey = null,
    ) {
    }
}
