<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\VatExclusiveStrategyInterface;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\Taxation;

class VatExclusiveStrategy implements VatExclusiveStrategyInterface, \App\ServiceInterface\Order\TaxationStrategyInterface
{
    public function compute(Money $net, Taxation $taxation): Money
    {
        if ('none' === $taxation->type || 0.0 === $taxation->rate) {
            return Money::zero($net->currency);
        }
        $tax = bcmul($net->amount, (string) $taxation->rate, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $net->currency);
    }
}
