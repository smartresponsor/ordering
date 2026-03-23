<?php

declare(strict_types=1);

namespace App\Service\Order\Adapter\Payment;

final class PayPalGateway implements PaymentGatewayInterface
{
    public function charge(string $orderId, string $amount, array $context = []): string
    {
        return 'paypal_'.substr(hash('sha256', $orderId.$amount), 0, 18);
    }

    public function refund(string $orderId, string $amount, array $context = []): string
    {
        return 'paypal_refund_'.substr(hash('sha256', $orderId.$amount), 0, 14);
    }
}
