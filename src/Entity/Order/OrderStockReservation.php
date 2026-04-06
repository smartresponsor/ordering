<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderStockReservation
{
    private bool $released = false;
    public function __construct(private string $orderId, private string $sku, private int $quantity) {}
    public function sku(): string { return $this->sku; }
    public function quantity(): int { return $this->quantity; }
    public function release(): void { $this->released = true; }
}
