<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ServiceInterface\Pricing\Order\TaxationStrategyInterface;
use App\ServiceInterface\Pricing\Order\VatExclusiveStrategyInterface;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Taxation;

class VatExclusiveStrategy implements VatExclusiveStrategyInterface, TaxationStrategyInterface
{
    public function compute(Money $net, Taxation $taxation): Money
    {
        if ('none' === $taxation->type || 0.0 === $taxation->rate) {
            return Money::zero($net->getCurrency());
        }
        $tax = bcmul($net->getAmount(), (string) $taxation->rate, 4);

        return new Money(number_format((float) $tax, 2, '.', ''), $net->getCurrency());
    }
}
