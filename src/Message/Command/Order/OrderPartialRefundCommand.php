<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

readonly class OrderPartialRefundCommand
{
    public function __construct(
        public string $orderId,
        public string $amount,
        public string $currency,
        public ?string $reason = null,
    ) {
    }
}
