<?php

declare(strict_types=1);

namespace App\Event\Order;

use App\Entity\Order;

final readonly class OrderFullyRefundedEvent
{
    public function __construct(
        public Order $order,
        public string $totalRefunded,
        public string $currency,
    ) {
    }
}
