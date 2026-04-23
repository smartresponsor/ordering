<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\ServiceInterface\Pricing\Order\PriceCalculatorServiceInterface;
use App\ServiceInterface\Pricing\Order\TaxationStrategyInterface;
use App\ValueObject\Pricing\Order\Discount;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Price;
use App\ValueObject\Pricing\Order\Taxation;

readonly class PriceCalculatorService implements PriceCalculatorServiceInterface
{
    public function __construct(private TaxationStrategyInterface $taxStrategy)
    {
    }

    public function calculateItemPrice(OrderItem $orderItem, Taxation $taxation, ?Discount $discount = null): Price
    {
        $base = new Money($orderItem->getBasePrice(), $orderItem->getCurrency());
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
