<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\ValueObject\Pricing\Order\Money;

interface FlatTaxStrategyInterface
{
    public function __construct(float $rate);

    public function tax(Money $taxableBase): Money;
}
