<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\Ordering\Entity\Order\OrderItemEntity;

interface LegacyTaxationStrategyInterface
{
    public function taxFor(OrderItemEntity $OrderItemEntity, int $priceAfterDiscount): int; // in minor units
}
