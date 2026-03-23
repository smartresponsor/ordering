<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderStockReservationInterface
{
    public function __construct(string $orderId, string $sku, int $quantity);

    public function id(): int;

    public function orderId(): string;

    public function sku(): string;

    public function quantity(): int;

    public function status(): string;

    public function markReleased(): void;

    public function markFailed(): void;
}
