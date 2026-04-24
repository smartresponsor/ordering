<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\ValueObject\Pricing\Order\Discount;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Price;
use App\ValueObject\Pricing\Order\Taxation;

interface PriceCalculatorServiceInterface
{
    public function __construct(TaxationStrategyInterface $taxStrategy);

    public function calculateItemPrice(OrderItem $orderItem, Taxation $taxation, ?Discount $discount = null): Price;

    public function calculateOrderTotals(Order $order, Taxation $taxation, ?Discount $discount = null): Money;
}
