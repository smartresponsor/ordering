<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\Entity\Order\OrderItemEntity;
use App\ServiceInterface\Pricing\Order\FlatTaxationStrategyInterface;
use App\ServiceInterface\Pricing\Order\LegacyTaxationStrategyInterface;

readonly class FlatTaxationStrategy implements LegacyTaxationStrategyInterface, FlatTaxationStrategyInterface
{
    public function __construct(private float $rate = 0.2)
    {
    }

    // 20%
    public function taxFor(OrderItemEntity $OrderItemEntity, int $priceAfterDiscount): int
    {
        return (int) round($priceAfterDiscount * $this->rate);
    }
}
