<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderRefundInitiatedEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $refundId,
        public readonly int $amountMinor,
        public readonly string $currency,
    ) {
    }
}
