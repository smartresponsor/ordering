<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\ServiceInterface\Order\PriceCalculatorServiceInterface;
use App\ValueObject\Order\Discount;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\Price;
use App\ValueObject\Order\Taxation;

class PriceCalculatorService implements PriceCalculatorServiceInterface
{
    public function __construct(private readonly \App\ServiceInterface\Order\TaxationStrategyInterface $taxStrategy)
    {
    }

    public function calculateItemPrice(OrderItem $orderItem, Taxation $taxation, ?Discount $discount = null): Price
    {
        $base = new Money((string) $orderItem->getBasePrice(), $orderItem->getCurrency());
        $tax = $this->taxStrategy->compute($base, $taxation);
        $afterTax = $base->add($tax);
        $final = $discount ? $discount->apply($afterTax) : $afterTax;
        $discountMoney = $afterTax->subtract($final);

        return Price::fromParts($afterTax, $tax, $discountMoney);
    }

    public function calculateOrderTotals(Order $order, Taxation $taxation, ?Discount $discount = null): Money
    {
        $sum = Money::zero($order->getCurrency());
        foreach ($order->getItems() as $item) {
            $p = $this->calculateItemPrice($item, $taxation, $discount);
            $sum = $sum->add($p->total);
        }

        return $sum;
    }
}
