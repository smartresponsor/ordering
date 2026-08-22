<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Pricing\Order;

use App\Ordering\Entity\Order\OrderItemEntity;

interface PromotionStrategyInterface
{
    public function discountFor(OrderItemEntity $OrderItemEntity): int; // returns discount in minor units
}
