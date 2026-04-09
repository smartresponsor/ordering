<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderPaidEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $amount,
        public readonly string $currency,
        public readonly string $externalRef,
    ) {
    }
}
