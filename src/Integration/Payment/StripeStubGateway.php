<?php

declare(strict_types=1);

namespace App\Integration\Payment;

use App\Contract\Order\OrderPaymentGatewayInterface;

final class StripeStubGateway implements OrderPaymentGatewayInterface
{
    public function __construct(private ?string $secret = null)
    {
    }

    public function charge(string $orderId, string $amount, array $context = []): string
    {
        return 'stripe_stub_'.substr(hash('sha256', $orderId.$amount.($this->secret ?? 'stub')), 0, 20);
    }

    public function refund(string $orderId, string $amount, array $context = []): string
    {
        return 'stripe_stub_refund_'.substr(hash('sha256', $orderId.$amount.($this->secret ?? 'stub')), 0, 16);
    }
}
