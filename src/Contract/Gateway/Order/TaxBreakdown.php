<?php

declare(strict_types=1);

namespace App\Contract\Gateway\Order;

final readonly class TaxBreakdown
{
    public function __construct(
        public readonly string $subtotal,
        public readonly string $taxAmount,
        public readonly string $total,
        public readonly string $currency,
    ) {
    }
}
