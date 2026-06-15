<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Entity\Order\OrderPaymentTransactionEntity;
use App\Entity\Order\OrderRefundTransactionEntity;
use App\Service\Refund\Order\OrderRefundService;
use App\ServiceInterface\Payment\Order\OrderPaymentServiceInterface;

final readonly class OrderPaymentService implements OrderPaymentServiceInterface
{
    public function __construct(
        private PartialPaymentService $partialPayments,
        private OrderRefundService $refunds,
    ) {
    }

    public function charge(float $amount): bool
    {
        return $amount >= 0;
    }

    public function applyPartialPayment(string $orderId, string $paymentId, string $currency, string $amount): OrderPaymentTransactionEntity
    {
        $method = '' !== strtolower($currency) ? strtolower($currency) : 'unknown';

        return $this->partialPayments->applyPartial($orderId, $amount, $method, $paymentId);
    }

    public function refund(string $orderId, string $refundId, string $currency, string $amount, ?string $reason = null): OrderRefundTransactionEntity
    {
        $transaction = $this->refunds->refund($orderId, $amount, $reason);

        return $transaction;
    }
}
