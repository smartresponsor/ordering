<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\TaxRate;

interface DefaultTaxationStrategyInterface
{
    public function tax(Money $taxBase, TaxRate $rate): Money;
}
