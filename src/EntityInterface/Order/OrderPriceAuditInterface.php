<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPriceAuditInterface
{
    public function __construct(string $id, string $orderId, string $currency, int $subtotalMinor, int $discountMinor, int $taxMinor, int $totalMinor, string $reason);

    public function id(): string;
}
