<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\PaymentGatewayInterface;
use App\ServiceInterface\Order\PayPalGatewayInterface;

final class PayPalGateway implements PaymentGatewayInterface, PayPalGatewayInterface
{
    public function __construct(private ?string $clientId = null, private ?string $secret = null)
    {
    }

    public function charge(string $orderId, string $amount, array $context = []): string
    {
        return 'paypal_'.substr(hash('sha256', $orderId.$amount.microtime()), 0, 18);
    }

    public function refund(string $orderId, string $amount, array $context = []): string
    {
        return 're_'.substr(hash('sha256', $orderId.$amount.microtime()), 0, 18);
    }
}
