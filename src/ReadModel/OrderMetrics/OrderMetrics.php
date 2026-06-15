<?php

declare(strict_types=1);

namespace App\ReadModel\OrderMetricsEntity;

final readonly class OrderMetricsEntity
{
    public function __construct(
        public string $orderId = '',
        public int $itemCount = 0,
        public string $total = '0.00',
    ) {
    }
}
