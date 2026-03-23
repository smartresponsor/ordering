<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use App\Service\Order\Adapter\Payment\PaymentGatewayInterface;

interface RefundServiceInterface
{
    public function __construct(
        PaymentGatewayInterface $gateway,
        OrderRefundTransactionRepositoryInterface $refunds,
    );

    public function refund(string $orderId, string $amount, ?string $reason = null): OrderRefundTransaction;
}
