<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Inventory\Order;

use App\ServiceInterface\Inventory\Order\InventoryGatewayInterface;

final class InMemoryInventoryGateway implements InventoryGatewayInterface
{
    /** @var array<string,int> */
    private array $stock = [];

    /** @var array<string,array<string,int>> */
    private array $reservations = [];

    public function __construct(array $initialStock = [])
    {
        $this->stock = $initialStock;
    }

    public function checkAvailability(array $lines): bool
    {
        return array_all($lines, fn ($qty, $sku) => ($this->stock[$sku] ?? 0) >= $qty);
    }

    public function reserve(string $reservationKey, array $lines): bool
    {
        if (isset($this->reservations[$reservationKey])) {
            return true;
        }

        if (!$this->checkAvailability($lines)) {
            return false;
        }

        foreach ($lines as $sku => $qty) {
            $this->stock[$sku] -= $qty;
        }

        $this->reservations[$reservationKey] = $lines;

        return true;
    }

    public function release(string $reservationKey): bool
    {
        $lines = $this->reservations[$reservationKey] ?? null;
        if (null === $lines) {
            return true;
        }

        foreach ($lines as $sku => $qty) {
            $this->stock[$sku] = ($this->stock[$sku] ?? 0) + $qty;
        }

        unset($this->reservations[$reservationKey]);

        return true;
    }

    public function consume(string $reservationKey): bool
    {
        unset($this->reservations[$reservationKey]);

        return true;
    }
}
