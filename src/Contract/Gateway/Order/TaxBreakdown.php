<?php

declare(strict_types=1);

namespace App\Contract\Gateway\Order;

final readonly class TaxBreakdown
{
    public function __construct(
        public string $subtotal,
        public string $taxAmount,
        public string $total,
        public string $currency,
    ) {
    }
}
