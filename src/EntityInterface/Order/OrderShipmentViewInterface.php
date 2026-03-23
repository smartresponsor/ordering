<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderShipmentViewInterface
{
    public function __construct(string $orderId, string $carrier, string $trackingNumber, string $status);

    public function update(string $carrier, string $tracking, string $status, ?\DateTimeImmutable $deliveredAt = null): void;
}
