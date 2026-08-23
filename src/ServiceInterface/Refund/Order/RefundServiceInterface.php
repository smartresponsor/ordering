<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Refund\Order;

use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use App\Ordering\ServiceInterface\Payment\Order\PaymentGatewayInterface;

interface RefundServiceInterface
{
    public function __construct(
        PaymentGatewayInterface $gateway,
        OrderRefundTransactionRepositoryInterface $refunds,
    );

    public function refund(string $orderId, string $amount, ?string $reason = null): OrderRefundTransactionEntity;
}
