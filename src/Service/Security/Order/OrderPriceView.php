<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final class OrderPriceView
{
    public function __construct(
        public string $orderId,
        public int $subtotalMinor,
        public int $discountMinor,
        public int $taxMinor,
        public int $totalMinor,
        public string $currency,
    ) {
    }
}
