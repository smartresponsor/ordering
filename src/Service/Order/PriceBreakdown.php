<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ValueObject\Order\Money;

final class PriceBreakdown
{
    public function __construct(
        public readonly Money $subtotal,
        public readonly Money $discount,
        public readonly Money $tax,
        public readonly Money $total,
    ) {
    }
}
