<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderRefundCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $amountMinor,
        public readonly string $currency,
        public readonly string $reason,
        public readonly ?string $paymentRef = null,
        public readonly ?string $idempotencyKey = null,
    ) {
    }
}
