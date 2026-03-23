<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderInterface
{
    public function __construct(string $id, int $totalAmount, string $currency, string $customerId, array $meta = []);

    public function id(): string;

    public function status(): OrderStatus;

    public function setStatus(OrderStatus $s): void;
}
