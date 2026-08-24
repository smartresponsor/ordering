<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderRefundCompletedEvent
{
    public function __construct(
        public string $orderId,
        public string $refundId,
        public string $amount,
        public string $currency,
        public string $externalRef,
        public string $occurredAt,
    ) {
    }
}
