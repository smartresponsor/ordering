<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order as RootOrder;

final class InventoryReservation
{
    public const string STATE_RESERVED = 'reserved';
    public const string STATE_RELEASED = 'released';
    public const string STATE_CONSUMED = 'consumed';
    public const string STATE_FAILED = 'failed';

    private string $orderId;
    private string $reservationKey;

    /** @var array<string, int> */
    private array $lines;

    private string $state = self::STATE_RESERVED;

    /**
     * @param RootOrder|string $order
     * @param array<string, int>|string $reservationKeyOrSku
     * @param array<string, int>|int $linesOrQuantity
     */
    public function __construct(
        private RootOrder|string $order,
        array|string $reservationKeyOrSku,
        array|int $linesOrQuantity,
    ) {
        if ($order instanceof RootOrder) {
            $this->orderId = (string) $order->id();
            $this->reservationKey = (string) $reservationKeyOrSku;
            $this->lines = is_array($linesOrQuantity) ? $linesOrQuantity : [];

            return;
        }

        $sku = (string) $reservationKeyOrSku;
        $quantity = (int) $linesOrQuantity;

        $this->orderId = $order;
        $this->reservationKey = $order.':'.$sku;
        $this->lines = [$sku => $quantity];
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function sku(): string
    {
        return (string) array_key_first($this->lines);
    }

    public function quantity(): int
    {
        return (int) ($this->lines[$this->sku()] ?? 0);
    }

    public function getReservationKey(): string
    {
        return $this->reservationKey;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function markReleased(): void
    {
        $this->state = self::STATE_RELEASED;
    }

    public function markConsumed(): void
    {
        $this->state = self::STATE_CONSUMED;
    }

    public function markFailed(): void
    {
        $this->state = self::STATE_FAILED;
    }
}
