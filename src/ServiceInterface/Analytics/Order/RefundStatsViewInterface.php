<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Analytics\Order;

interface RefundStatsViewInterface
{
    public function addRefund(string $amount): void;
}
