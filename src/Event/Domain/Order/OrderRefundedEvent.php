<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderRefundedEvent
{
    public function __construct(
        public string $orderId,
        public string $amount,
        public string $currency,
        public ?string $vendorId = null,
    ) {
    }
}
