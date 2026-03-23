<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

interface InMemoryInventoryGatewayInterface
{
    public function __construct(array $initialStock = []);

    public function reserve(string $orderId, string $sku, int $qty): bool;

    public function release(string $orderId, string $sku, int $qty): bool;

    public function checkAvailable(string $sku, int $qty): bool;
}
