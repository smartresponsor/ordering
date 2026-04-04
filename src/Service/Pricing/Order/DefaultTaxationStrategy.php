<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\TaxRate;

class DefaultTaxationStrategy implements \App\ServiceInterface\Pricing\Order\DefaultTaxationStrategyInterface
{
    public function tax(Money $taxBase, TaxRate $rate): Money
    {
        return new Money(
            bcmul($taxBase->getAmount(), $rate->asDecimal(), 6),
            $taxBase->getCurrency(),
        );
    }
}
