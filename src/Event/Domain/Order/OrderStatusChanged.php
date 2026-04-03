<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

final class OrderStatusChanged
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $fromStatus,
        public readonly string $toStatus,
    ) {
    }
}
