<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

interface InventoryGatewayInterface
{
    /** @param array<string,int> $lines sku => qty */
    public function checkAvailability(array $lines): bool;

    /** Idempotent reservation by key; returns true if reserved or already reserved */
    public function reserve(string $reservationKey, array $lines): bool;

    /** Release by key; returns true if released or already released */
    public function release(string $reservationKey): bool;

    /** Commit consumption by key after shipment */
    public function consume(string $reservationKey): bool;
}
