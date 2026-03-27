<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\DefaultPromotionStrategyInterface;
use App\ValueObject\Order\Discount;
use App\ValueObject\Order\Money;

class DefaultPromotionStrategy implements DefaultPromotionStrategyInterface
{
    public function __construct(private readonly ?Discount $discount = null)
    {
    }

    public function discount(Money $subtotal): Money
    {
        if (null === $this->discount) {
            return Money::zero($subtotal->getCurrency());
        }

        $afterDiscount = $this->discount->apply($subtotal);

        return $subtotal->subtract($afterDiscount);
    }
}
