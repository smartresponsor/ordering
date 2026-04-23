<?php

declare(strict_types=1);

namespace App\ValueObject\Pricing\Order;

final readonly class PriceBreakdown
{
    public function __construct(
        public Money $subtotal,
        public Money $discount,
        public Money $tax,
        public Money $total,
    ) {
    }
}
