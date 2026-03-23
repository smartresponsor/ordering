<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface InventoryReservationInterface
{
    public function __construct(Order $order, string $reservationKey, array $lines);

    public function getReservationKey(): string;

    public function getState(): string;

    public function markReleased(): void;

    public function markConsumed(): void;

    public function getLines(): array;

    public function getOrder(): Order;
}
