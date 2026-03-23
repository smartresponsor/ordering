<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\ValueObject\Order\Money;

interface ProgressiveTaxStrategyInterface
{
    public function __construct(array $brackets);

    public function tax(Money $taxableBase): Money;
}
