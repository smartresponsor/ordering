<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Inventory\Order;

use App\Entity\Order;
use App\Entity\Order\InventoryReservation;

interface InventoryServiceInterface
{
    /** @param array<string,int> $lines sku => qty */
    public function reserve(Order $order, array $lines, string $key): InventoryReservation;

    public function release(InventoryReservation $res): void;

    public function consume(InventoryReservation $res): void;
}
