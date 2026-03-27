<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

/**
 * Payment gateway contract for Ordering service layer.
 */
interface OrderPaymentGatewayInterface
{
    public function initiatePayment(string $orderNumber, float $amount, string $currency): string;

    public function refundPayment(string $orderNumber, float $amount): bool;

    public function getPaymentStatus(string $orderNumber): ?string;
}
