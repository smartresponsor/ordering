<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

final readonly class OrderRefundCommand
{
    public function __construct(
        public string $orderId,
        public int $amountMinor,
        public string $currency,
        public string $reason,
        public ?string $paymentRef = null,
        public ?string $idempotencyKey = null,
    ) {
    }
}
