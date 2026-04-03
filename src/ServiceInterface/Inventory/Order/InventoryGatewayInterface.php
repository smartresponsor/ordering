<?php

declare(strict_types=1);

namespace App\ServiceInterface\Inventory\Order;

interface InventoryGatewayInterface
{
    /** @param array<string,int> $lines sku => qty */
    public function checkAvailability(array $lines): bool;

    /** @param array<string,int> $lines sku => qty */
    public function reserve(string $reservationKey, array $lines): bool;

    public function release(string $reservationKey): bool;

    public function consume(string $reservationKey): bool;
}
