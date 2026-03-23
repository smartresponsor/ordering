<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Service\Order\CurrencyConversionService;
use App\Service\Order\TaxationConfigLoader;
use App\Service\Order\TaxationStrategyInterface;
use App\ValueObject\Order\Currency;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\TaxRate;

interface PriceCalculatorInterface
{
    public function __construct(
        DefaultPromotionStrategyInterface $promotions,
        TaxationStrategyInterface $taxation,
        TaxationConfigLoader $taxConfig,
        CurrencyConversionService $fx,
    );

    public function calculate(Money $subtotal, TaxRate $rate, ?Currency $targetCurrency = null): array;
}
