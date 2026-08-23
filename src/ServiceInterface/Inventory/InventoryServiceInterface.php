<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Inventory;

interface InventoryServiceInterface
{
    public function reserve(array $items): void;

    public function release(array $items): void;

    public function getReserved(string $sku): int;
}
