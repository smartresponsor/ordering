<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\ServiceInterface\Payment\Order\PaymentGatewayInterface;
use App\ServiceInterface\Payment\Order\StripeGatewayInterface;

final readonly class StripeGateway implements PaymentGatewayInterface, StripeGatewayInterface
{
    public function __construct(private ?string $apiKey = null)
    {
    }

    public function charge(string $orderId, string $amount, array $context = []): string
    {
        return 'stripe_'.substr(hash('sha256', $orderId.$amount.microtime()), 0, 18);
    }

    public function refund(string $orderId, string $amount, array $context = []): string
    {
        return 're_'.substr(hash('sha256', $orderId.$amount.microtime()), 0, 18);
    }
}
