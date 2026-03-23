<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface InventoryReservationFlowTestInterface
{
    public function test_reserve_release_consume(): void;

    public function test_not_enough_stock_fails(): void;
}
