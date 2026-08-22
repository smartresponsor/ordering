<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\ServiceInterface\Pricing\Order\DefaultPromotionStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Discount;
use App\Ordering\ValueObject\Pricing\Order\Money;

readonly class DefaultPromotionStrategy implements DefaultPromotionStrategyInterface
{
    public function __construct(private ?Discount $discount = null)
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
