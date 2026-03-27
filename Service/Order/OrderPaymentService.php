<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderPaymentTransaction;
use App\Entity\Order\OrderRefundTransaction;
use App\ServiceInterface\Order\OrderPaymentServiceInterface;

final class OrderPaymentService implements OrderPaymentServiceInterface
{
    public function __construct(
        private readonly PartialPaymentService $partialPayments,
        private readonly RefundService $refunds,
    ) {
    }

    public function charge(float $amount): bool
    {
        return $amount >= 0;
    }

    public function applyPartialPayment(string $orderId, string $paymentId, string $currency, string $amount): OrderPaymentTransaction
    {
        $method = '' !== strtolower($currency) ? strtolower($currency) : 'unknown';

        return $this->partialPayments->applyPartial($orderId, $amount, $method, $paymentId);
    }

    public function refund(string $orderId, string $refundId, string $currency, string $amount, ?string $reason = null): OrderRefundTransaction
    {
        $transaction = $this->refunds->refund($orderId, $amount, $reason);

        return $transaction;
    }
}
