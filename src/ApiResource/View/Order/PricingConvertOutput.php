<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

final readonly class PricingConvertOutput
{
    public function __construct(
        public int $subtotalMinor,
        public int $discountMinor,
        public int $taxMinor,
        public int $totalMinor,
        public string $currency,
    ) {
    }
}
