<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final readonly class OrderRefundCompletedEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly ?string $refundId = null,
        public readonly ?string $amount = null,
    ) {
    }
}
