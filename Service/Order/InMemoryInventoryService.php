<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\InventoryServiceInterface;

final class InMemoryInventoryService implements InventoryServiceInterface
{
    /** @var array<string,int> */
    private array $reserved = [];

    public function reserve(array $items): void
    {
        foreach ($items as $it) {
            $sku = (string) (new \ReflectionProperty($it, 'sku'))->getValue($it);
            $this->reserved[$sku] = ($this->reserved[$sku] ?? 0) + $it->getQuantity();
        }
    }

    public function release(array $items): void
    {
        foreach ($items as $it) {
            $sku = (string) (new \ReflectionProperty($it, 'sku'))->getValue($it);
            $this->reserved[$sku] = max(0, ($this->reserved[$sku] ?? 0) - $it->getQuantity());
        }
    }

    /** @internal for tests */
    public function getReserved(string $sku): int
    {
        return $this->reserved[$sku] ?? 0;
    }
}
