<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

use App\ValueObject\Pricing\Order\Money;

final readonly class PriceBreakdown
{
    public function __construct(
        public readonly Money $subtotal,
        public readonly Money $discount,
        public readonly Money $tax,
        public readonly Money $total,
    ) {
    }
}
