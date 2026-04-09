<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

readonly class OrderPartialRefundCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $amount,
        public readonly string $currency,
        public readonly ?string $reason = null,
    ) {
    }
}
