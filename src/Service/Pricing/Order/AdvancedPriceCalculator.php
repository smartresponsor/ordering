<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ServiceInterface\Pricing\Order\CurrencyConversionServiceInterface;
use App\ServiceInterface\Pricing\Order\LegacyTaxationStrategyInterface;
use App\ValueObject\Pricing\Order\Discount;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\PriceBreakdown;

final readonly class AdvancedPriceCalculator
{
    public function __construct(
        private readonly CurrencyConversionServiceInterface $fx,
        private readonly bool $taxAfterDiscount = true,
    ) {
    }

    /** @param array<int,array{priceMinor:int, quantity:int, currency?:string}> $items */
    public function calculate(
        array $items,
        string $displayCurrency,
        ?Discount $discount,
        ?LegacyTaxationStrategyInterface $taxStrategy,
    ): PriceBreakdown {
        $displayCurrency = strtoupper($displayCurrency);
        $subtotal = Money::zero($displayCurrency);

        foreach ($items as $i) {
            $minor = (int) $i['priceMinor'] * max(1, (int) $i['quantity']);
            $itemMoney = new Money(number_format($minor / 100, 2, '.', ''), strtoupper($i['currency'] ?? $displayCurrency));
            $itemMoney = $this->fx->convert($itemMoney, $displayCurrency);
            $subtotal = $subtotal->add($itemMoney);
        }

        $discountedBase = $discount ? $discount->apply($subtotal) : $subtotal;
        $discountMoney = $subtotal->subtract($discountedBase);

        $taxBase = $this->taxAfterDiscount ? $discountedBase : $subtotal;
        $tax = Money::zero($displayCurrency);
        if (null !== $taxStrategy) {
            foreach ($items as $i) {
                $item = new \App\Entity\Order\OrderItem((string) ($i['sku'] ?? 'sku'), (int) $i['quantity'], (int) $i['priceMinor'], strtoupper((string) ($i['currency'] ?? $displayCurrency)));
                $taxMinor = $taxStrategy->taxFor($item, (int) $i['priceMinor']);
                $tax = $tax->add(new Money(number_format($taxMinor / 100, 2, '.', ''), $displayCurrency));
            }
        }
        $total = $taxBase->add($tax);

        return new PriceBreakdown($subtotal, $discountMoney, $tax, $total);
    }
}
