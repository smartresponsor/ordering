<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderPaidEvent
{
    public function __construct(
        public string $orderId,
        public string $amount,
        public string $currency,
        public string $externalRef,
    ) {
    }
}
