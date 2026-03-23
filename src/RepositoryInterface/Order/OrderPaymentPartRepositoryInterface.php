<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

interface OrderPaymentPartRepositoryInterface
{
    public function add(OrderPaymentPart $p): void;

    public function listByOrder(string $orderId): array;

    public function sumByOrder(string $orderId): string;
}
