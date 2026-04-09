<?php

declare(strict_types=1);

namespace App\ReadModel\OrderMetrics;

final readonly class OrderMetrics
{
    public function __construct(
        public string $orderId = '',
        public int $itemCount = 0,
        public string $total = '0.00',
    ) {
    }
}
