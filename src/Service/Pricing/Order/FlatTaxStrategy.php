<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\FlatTaxStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;

final readonly class FlatTaxStrategy implements FlatTaxStrategyInterface
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
