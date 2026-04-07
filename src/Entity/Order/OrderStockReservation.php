<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderStockReservation
{
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_FAILED = 'failed';
    public const STATUS_RELEASED = 'released';

    private string $status = self::STATUS_RESERVED;

    public function __construct(private string $orderId, private string $sku, private int $quantity)
    {
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function isReleased(): bool
    {
        return self::STATUS_RELEASED === $this->status;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function release(): void
    {
        $this->markReleased();
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS_FAILED;
    }

    public function markReleased(): void
    {
        $this->status = self::STATUS_RELEASED;
    }
}
