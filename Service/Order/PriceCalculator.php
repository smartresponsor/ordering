<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\CurrencyConversionServiceInterface;
use App\ServiceInterface\Order\DefaultPromotionStrategyInterface;
use App\ServiceInterface\Order\TaxationConfigLoaderInterface;
use App\ServiceInterface\Order\TaxationStrategyInterface;
use App\ValueObject\Order\Currency;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\TaxRate;

class PriceCalculator implements \App\ServiceInterface\Order\PriceCalculatorInterface
{
    public function __construct(
        private readonly DefaultPromotionStrategyInterface $promotions,
        private readonly TaxationStrategyInterface $taxation,
        private readonly TaxationConfigLoaderInterface $taxConfig,
        private readonly CurrencyConversionServiceInterface $fx,
    ) {
    }

    /**
     * @param Money         $subtotal       money in order's currency
     * @param TaxRate       $rate           tax rate (from config)
     * @param Currency|null $targetCurrency convert final totals to this currency
     */
    public function calculate(Money $subtotal, TaxRate $rate, ?Currency $targetCurrency = null): array
    {
        $discount = $this->promotions->discount($subtotal);
        $taxBase = $subtotal->subtract($discount);
        $tax = $this->taxation->tax($taxBase, $rate);
        $total = $taxBase->add($tax);

        $scale = $this->taxConfig->rounding();
        $subtotal = $subtotal->round($scale);
        $discount = $discount->round($scale);
        $tax = $tax->round($scale);
        $total = $total->round($scale);

        if ($targetCurrency) {
            $subtotal = $this->fx->convert($subtotal, $targetCurrency, $scale);
            $discount = $this->fx->convert($discount, $targetCurrency, $scale);
            $tax = $this->fx->convert($tax, $targetCurrency, $scale);
            $total = $this->fx->convert($total, $targetCurrency, $scale);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ];
    }
}
