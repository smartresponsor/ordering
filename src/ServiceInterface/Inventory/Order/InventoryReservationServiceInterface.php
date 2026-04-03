<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Inventory\Order;

use App\Entity\Order\OrderStockReservation;

interface InventoryReservationServiceInterface
{
    public function reserveOrFail(string $orderId, string $sku, int $qty): OrderStockReservation;

    public function release(string $orderId, string $sku, int $qty): void;
}
