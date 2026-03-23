<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Service\Order\Taxation\TaxationStrategyInterface;
use App\ValueObject\Order\DiscountRule;
use App\ValueObject\Order\Money;

final class AdvancedPriceCalculator
{
    public function __construct(
        private CurrencyConversionService $fx,
        private bool $taxAfterDiscount = true,
    ) {
    }

    /** @param array<int,array{priceMinor:int, quantity:int, currency?:string}> $items */
    public function calculate(
        array $items,
        string $displayCurrency,
        ?DiscountRule $discount,
        ?TaxationStrategyInterface $taxStrategy,
    ): PriceBreakdown {
        $displayCurrency = strtoupper($displayCurrency);
        $subtotal = new Money(0, $displayCurrency);

        // 1) Sum line items (convert each to displayCurrency if needed)
        foreach ($items as $i) {
            $itemMoney = new Money((int) $i['priceMinor'] * max(1, (int) $i['quantity']), strtoupper($i['currency'] ?? $displayCurrency));
            $itemMoney = $this->fx->convert($itemMoney, $displayCurrency);
            $subtotal = $subtotal->add($itemMoney);
        }

        // 2) Discount applied on subtotal (in display currency)
        $discountMoney = new Money(0, $displayCurrency);
        if ($discount) {
            if (null !== $discount->amountMinor) {
                $discountMoney = new Money(min($subtotal->amountMinor(), $discount->amountMinor), $displayCurrency);
            } elseif (null !== $discount->percent) {
                $discountMoney = new Money((int) round($subtotal->amountMinor() * $discount->percent), $displayCurrency);
            }
        }

        // 3) Tax base depends on config
        $taxBase = $this->taxAfterDiscount ? $subtotal->sub($discountMoney) : $subtotal;
        $tax = new Money(0, $displayCurrency);
        if ($taxStrategy) {
            $tax = $taxStrategy->tax($taxBase);
        }

        // 4) Total
        $total = $taxBase->add($tax);

        return new PriceBreakdown($subtotal, $discountMoney, $tax, $total);
    }
}
