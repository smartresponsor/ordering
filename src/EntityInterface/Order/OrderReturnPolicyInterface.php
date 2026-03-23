<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderReturnPolicyInterface
{
    public function __construct(OrderShipment $shipment, int $daysAllowed = 14);

    public function getAutoExpireDate(): ?\DateTimeImmutable;

    public function getDaysAllowed(): int;

    public function setDeliveredRecalculate(): void;
}
