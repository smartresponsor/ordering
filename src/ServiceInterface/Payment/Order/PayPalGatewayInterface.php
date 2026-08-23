<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Payment\Order;

interface PayPalGatewayInterface
{
    public function __construct(?string $clientId = null, ?string $secret = null);

    public function charge(string $orderId, string $amount, array $context = []): string;

    public function refund(string $orderId, string $amount, array $context = []): string;
}
