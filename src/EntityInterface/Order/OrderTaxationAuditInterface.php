<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderTaxationAuditInterface
{
    public function __construct(Order $order, string $subtotal, string $taxTotal, string $discountTotal, string $finalTotal);
}
