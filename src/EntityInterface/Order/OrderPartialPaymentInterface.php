<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPartialPaymentInterface
{
    public function __construct(string $id, string $orderId, int $amountMinor, string $currency, string $paymentMethod);

    public function id(): string;
}
