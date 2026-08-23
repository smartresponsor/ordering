<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Pricing\Order;

use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\Taxation;

interface VatInclusiveStrategyInterface
{
    public function compute(Money $gross, Taxation $taxation): Money;
}
