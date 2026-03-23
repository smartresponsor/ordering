<?php

declare(strict_types=1);

namespace App\Service\Order\OrderPricing;

final class AdvancedPriceCalculator
{
    public function __construct(private readonly bool $taxAfterDiscount = true)
    {
    }

    public function calculate(string $subtotal, string $discount, string $taxRate): array
    {
        $base = (float) $subtotal - (float) $discount;
        $taxBase = $this->taxAfterDiscount ? $base : (float) $subtotal;
        $tax = $taxBase * (float) $taxRate;

        return [
            'subtotal' => number_format((float) $subtotal, 2, '.', ''),
            'discount' => number_format((float) $discount, 2, '.', ''),
            'tax' => number_format($tax, 2, '.', ''),
            'total' => number_format($base + $tax, 2, '.', ''),
        ];
    }
}
