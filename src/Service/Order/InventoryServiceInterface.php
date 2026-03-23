<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderItem;

interface InventoryServiceInterface
{
    /** @param OrderItem[] $items */
    public function reserve(array $items): void;

    /** @param OrderItem[] $items */
    public function release(array $items): void;
}
