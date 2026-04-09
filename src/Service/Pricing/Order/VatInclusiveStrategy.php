<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ServiceInterface\Pricing\Order\VatInclusiveStrategyInterface;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Taxation;

final class VatInclusiveStrategy implements VatInclusiveStrategyInterface
{
    public function compute(Money $gross, Taxation $taxation): Money
    {
        if (0.0 === $taxation->rate) {
            return Money::zero($gross->getCurrency());
        }

        $den = bcadd('1', (string) $taxation->rate, 6);
        $net = bcdiv($gross->getAmount(), $den, 4);
        $tax = bcsub($gross->getAmount(), $net, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $gross->getCurrency());
    }
}
