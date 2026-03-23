<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

interface InMemoryInventoryServiceInterface
{
    public function reserve(array $items): void;

    public function release(array $items): void;

    public function getReserved(string $sku): int;
}
