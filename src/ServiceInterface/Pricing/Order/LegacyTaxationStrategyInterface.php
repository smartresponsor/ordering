<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Pricing\Order;

use App\Entity\Order\OrderItem;

interface LegacyTaxationStrategyInterface
{
    public function taxFor(OrderItem $orderItem, int $priceAfterDiscount): int; // in minor units
}
