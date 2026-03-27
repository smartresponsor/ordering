<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\ValueObject\Order\Discount;
use App\ValueObject\Order\PriceBreakdown;

interface AdvancedPriceCalculatorInterface
{
    /** @param array<int,array{priceMinor:int, quantity:int, currency?:string}> $items */
    public function calculate(
        array $items,
        string $displayCurrency,
        ?Discount $discount,
        ?LegacyTaxationStrategyInterface $taxStrategy,
    ): PriceBreakdown;
}
