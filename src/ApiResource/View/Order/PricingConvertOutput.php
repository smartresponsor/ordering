<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

final readonly class PricingConvertOutput
{
    public function __construct(
        public readonly int $subtotalMinor,
        public readonly int $discountMinor,
        public readonly int $taxMinor,
        public readonly int $totalMinor,
        public readonly string $currency,
    ) {
    }
}
