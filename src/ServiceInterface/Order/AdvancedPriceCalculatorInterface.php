<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Service\Order\CurrencyConversionService;
use App\Service\Order\PriceBreakdown;
use App\Service\Order\Taxation\TaxationStrategyInterface;
use App\ValueObject\Order\DiscountRule;

interface AdvancedPriceCalculatorInterface
{
    public function __construct(
        CurrencyConversionService $fx,
        bool $taxAfterDiscount = true,
    );

    public function calculate(
        array $items,
        string $displayCurrency,
        ?DiscountRule $discount,
        ?TaxationStrategyInterface $taxStrategy,
    ): PriceBreakdown;
}
