<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Order;

interface UPSGatewayInterface
{
    public function createShipment(Order $order, string $carrier): string;

    public function updateShipmentStatus(string $trackingNumber, string $status): bool;

    public function getShipmentStatus(string $trackingNumber): ?string;
}
