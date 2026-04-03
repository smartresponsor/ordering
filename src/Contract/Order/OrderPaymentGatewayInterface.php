<?php

declare(strict_types=1);

namespace App\Contract\Order;

interface OrderPaymentGatewayInterface
{
    /** @param array<string, mixed> $context */
    public function charge(string $orderId, string $amount, array $context = []): string;

    /** @param array<string, mixed> $context */
    public function refund(string $orderId, string $amount, array $context = []): string;
}
