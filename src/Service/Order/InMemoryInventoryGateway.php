<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\InventoryGatewayInterface;

final class InMemoryInventoryGateway implements InventoryGatewayInterface
{
    /** @var array<string,int> */
    private array $stock = [];

    public function __construct(array $initialStock = [])
    {
        $this->stock = $initialStock;
    }

    public function reserve(string $orderId, string $sku, int $qty): bool
    {
        if (($this->stock[$sku] ?? 0) < $qty) {
            return false;
        }
        $this->stock[$sku] -= $qty;

        return true;
    }

    public function release(string $orderId, string $sku, int $qty): bool
    {
        $this->stock[$sku] = ($this->stock[$sku] ?? 0) + $qty;

        return true;
    }

    public function checkAvailable(string $sku, int $qty): bool
    {
        return ($this->stock[$sku] ?? 0) >= $qty;
    }
}
