<?php

declare(strict_types=1);

namespace App\Service\Order;

/**
 * Legacy service-layer payment gateway contract kept for compatibility with older tests.
 * Prefer App\Contract\Order\OrderPaymentGatewayInterface in new code.
 */
interface OrderPaymentGatewayInterface
{
    public function initiatePayment(string $orderNumber, float $amount, string $currency): string;

    public function refundPayment(string $orderNumber, float $amount): bool;

    public function getPaymentStatus(string $orderNumber): ?string;
}
