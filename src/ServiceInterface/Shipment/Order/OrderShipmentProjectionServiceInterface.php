<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Shipment\Order;

interface OrderShipmentProjectionServiceInterface
{
    public function updateFromExternal(
        string $orderId,
        string $carrier,
        string $tracking,
        string $status,
        ?\DateTimeImmutable $deliveredAt = null,
    ): void;
}
