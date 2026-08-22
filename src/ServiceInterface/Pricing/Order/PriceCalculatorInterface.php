<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Pricing\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderItemEntity;
use App\Ordering\ValueObject\Pricing\Order\Currency;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\TaxRate;

interface PriceCalculatorInterface
{
    public function __construct(
        DefaultPromotionStrategyInterface $promotions,
        TaxationStrategyInterface $taxation,
        TaxationConfigLoaderInterface $taxConfig,
        CurrencyConversionServiceInterface $fx,
    );

    public function calculate(Money $subtotal, TaxRate $rate, ?Currency $targetCurrency = null): array;

    /** @param OrderItemEntity[] $items */
    public function recalc(OrderEntity $OrderEntity, array $items): void;
}
