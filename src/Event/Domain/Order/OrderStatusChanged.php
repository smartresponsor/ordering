<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

final readonly class OrderStatusChanged
{
    public function __construct(
        public string $orderId,
        public string $fromStatus,
        public string $toStatus,
    ) {
    }
}
