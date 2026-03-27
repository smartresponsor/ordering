<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderItem;

class FlatTaxationStrategy implements TaxationStrategyInterface, \App\ServiceInterface\Order\FlatTaxationStrategyInterface
{
    public function __construct(private readonly float $rate = 0.2)
    {
    }

    // 20%
    public function taxFor(OrderItem $orderItem, int $priceAfterDiscount): int
    {
        return (int) round($priceAfterDiscount * $this->rate);
    }
}
