<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderPriceView
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $subtotalMinor,
        public readonly int $discountMinor,
        public readonly int $taxMinor,
        public readonly int $totalMinor,
        public readonly string $currency,
    ) {
    }
}
