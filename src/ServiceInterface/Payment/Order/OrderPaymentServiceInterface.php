<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Payment\Order;

use App\Ordering\Entity\Order\OrderPaymentTransactionEntity;
use App\Ordering\Entity\Order\OrderRefundTransactionEntity;

interface OrderPaymentServiceInterface
{
    public function charge(float $amount): bool;

    public function applyPartialPayment(string $orderId, string $paymentId, string $currency, string $amount): OrderPaymentTransactionEntity;

    public function refund(string $orderId, string $refundId, string $currency, string $amount, ?string $reason = null): OrderRefundTransactionEntity;
}
