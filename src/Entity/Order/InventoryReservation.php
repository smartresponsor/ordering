<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class InventoryReservation
{
    public function __construct(private string $orderId, private string $sku, private int $quantity) {}
    public function orderId(): string { return $this->orderId; }
    public function sku(): string { return $this->sku; }
    public function quantity(): int { return $this->quantity; }
}
