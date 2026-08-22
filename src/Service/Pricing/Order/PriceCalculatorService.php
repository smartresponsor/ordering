<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Pricing\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderItemEntity;
use App\Ordering\ServiceInterface\Pricing\Order\PriceCalculatorServiceInterface;
use App\Ordering\ServiceInterface\Pricing\Order\TaxationStrategyInterface;
use App\Ordering\ValueObject\Pricing\Order\Discount;
use App\Ordering\ValueObject\Pricing\Order\Money;
use App\Ordering\ValueObject\Pricing\Order\Price;
use App\Ordering\ValueObject\Pricing\Order\Taxation;

readonly class PriceCalculatorService implements PriceCalculatorServiceInterface
{
    public function __construct(private TaxationStrategyInterface $taxStrategy)
    {
    }

    public function calculateItemPrice(OrderItemEntity $OrderItemEntity, Taxation $taxation, ?Discount $discount = null): Price
    {
        $base = new Money($OrderItemEntity->getBasePrice(), $OrderItemEntity->getCurrency());
        $tax = $this->taxStrategy->compute($base, $taxation);
        $afterTax = $base->add($tax);
        $final = $discount ? $discount->apply($afterTax) : $afterTax;
        $discountMoney = $afterTax->subtract($final);

        return Price::fromParts($afterTax, $tax, $discountMoney);
    }

    public function calculateOrderTotals(OrderEntity $order, Taxation $taxation, ?Discount $discount = null): Money
    {
        $sum = Money::zero($order->getCurrency());
        foreach ($order->getItems() as $item) {
            $p = $this->calculateItemPrice($item, $taxation, $discount);
            $sum = $sum->add($p->total);
        }

        return $sum;
    }
}
