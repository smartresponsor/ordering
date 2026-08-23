<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\DefaultTaxationStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\TaxRate;

class DefaultTaxationStrategy implements DefaultTaxationStrategyInterface
{
    public function tax(Money $taxBase, TaxRate $rate): Money
    {
        return new Money(
            bcmul($taxBase->getAmount(), $rate->asDecimal(), 6),
            $taxBase->getCurrency(),
        );
    }
}
