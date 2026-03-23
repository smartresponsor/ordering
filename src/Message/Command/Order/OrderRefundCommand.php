<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

final class OrderRefundCommand
{
    public function __construct(
        public string $orderId,
        public int $amountMinor,
        public string $currency,
        public ?string $reason = null,
        public ?string $paymentRef = null,
        public ?string $idempotencyKey = null,
    ) {
    }
}
