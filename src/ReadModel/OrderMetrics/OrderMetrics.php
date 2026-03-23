<?php

declare(strict_types=1);

namespace App\ReadModel\OrderMetrics;

final class OrderMetrics
{
    public function __construct(
        public readonly string $orderId = '',
        public readonly int $itemCount = 0,
        public readonly string $total = '0.00',
    ) {
    }
}
