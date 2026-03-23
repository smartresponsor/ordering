<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\VatInclusiveStrategyInterface;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\Taxation;

final class VatInclusiveStrategy implements VatInclusiveStrategyInterface
{
    public function compute(Money $gross, Taxation $taxation): Money
    {
        if ('none' === $taxation->type || 0.0 === $taxation->rate) {
            return Money::zero($gross->currency);
        }
        // tax part already inside gross
        $den = bcadd('1', (string) $taxation->rate, 4);
        $net = bcdiv($gross->amount, $den, 4);
        $tax = bcsub($gross->amount, $net, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $gross->currency);
    }
}
