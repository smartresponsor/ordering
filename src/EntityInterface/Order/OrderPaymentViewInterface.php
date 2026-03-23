<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPaymentViewInterface
{
    public function __construct(string $orderId, string $paymentId, int $amountMinor, string $currency, string $status);

    public function updateStatus(string $status): void;
}
