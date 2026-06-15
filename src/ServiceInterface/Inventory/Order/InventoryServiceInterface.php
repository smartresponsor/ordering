<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Inventory\Order;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderStockReservationEntity;

interface InventoryServiceInterface
{
    /** @param array<string,int> $lines sku => qty */
    public function reserve(OrderEntity $OrderEntity, array $lines, string $key): OrderStockReservationEntity;

    public function release(OrderStockReservationEntity $res): void;

    public function consume(OrderStockReservationEntity $res): void;
}
