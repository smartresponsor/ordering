<?php

declare(strict_types=1);

namespace App\Contract\Order;

interface OrderPaymentGatewayInterface
{
    public function charge(string $orderId, string $amount, array $context = []): string;

    public function refund(string $orderId, string $amount, array $context = []): string;
}
