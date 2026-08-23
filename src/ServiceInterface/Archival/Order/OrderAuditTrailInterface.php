<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Archival\Order;

interface OrderAuditTrailInterface
{
    public function buildForOrder(string $orderId): OrderAuditTrail;
}
