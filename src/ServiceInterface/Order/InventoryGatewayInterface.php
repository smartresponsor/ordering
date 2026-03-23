<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

interface InventoryGatewayInterface
{
    public function reserve(string $orderId, string $sku, int $qty): bool;

    public function release(string $orderId, string $sku, int $qty): bool;

    public function checkAvailable(string $sku, int $qty): bool;
}
