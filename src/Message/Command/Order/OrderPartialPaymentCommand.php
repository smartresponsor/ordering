<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final readonly class OrderPartialPaymentCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $amountMinor,
        public readonly string $currency,
        public readonly string $paymentMethod,
        public readonly ?string $idempotencyKey = null,
    ) {
    }
}
