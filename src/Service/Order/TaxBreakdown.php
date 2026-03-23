<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order\Order;

final class TaxBreakdown
{
    public function __construct(
        public readonly string $subtotal,
        public readonly string $taxAmount,
        public readonly string $total,
        public readonly string $currency,
    ) {
    }
}

interface OrderTaxationGatewayInterface
{
    /**
     * Calculate taxes for the given order and return a breakdown.
     */
    public function calculate(Order $order, ?string $countryCode = null): TaxBreakdown;
}
