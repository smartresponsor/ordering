<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\NoTaxStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\Taxation;

final class NoTaxStrategy implements NoTaxStrategyInterface
{
    public function compute(Money $net, Taxation $taxation): Money
    {
        return Money::zero($net->getCurrency());
    }
}
