<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\ValueObject\Order\Discount;
use App\ValueObject\Order\Money;

interface DefaultPromotionStrategyInterface
{
    public function __construct(?Discount $discount = null);

    public function discount(Money $subtotal): Money;
}
