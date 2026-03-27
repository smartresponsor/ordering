<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\FlatTaxStrategyInterface;
use App\ValueObject\Order\Money;

final class FlatTaxStrategy implements FlatTaxStrategyInterface
{
    public function __construct(private float $rate)
    {
        if ($rate < 0 || $rate > 1) {
            throw new \InvalidArgumentException('Invalid tax rate');
        }
    }

    public function tax(Money $taxableBase): Money
    {
        $amount = bcmul($taxableBase->getAmount(), (string) $this->rate, 2);

        return new Money($amount, $taxableBase->getCurrency());
    }
}
