<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderRefundInitiatedEvent
{
    public function __construct(
        public string $orderId,
        public string $refundId,
        public int $amountMinor,
        public string $currency,
    ) {
    }
}
