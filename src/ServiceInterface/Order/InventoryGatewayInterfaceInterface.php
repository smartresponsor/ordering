<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface InventoryGatewayInterfaceInterface
{
    public function checkAvailability(array $lines): bool;

    public function reserve(string $reservationKey, array $lines): bool;

    public function release(string $reservationKey): bool;

    public function consume(string $reservationKey): bool;
}
