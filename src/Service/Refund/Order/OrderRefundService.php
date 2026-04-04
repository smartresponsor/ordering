<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Refund\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use App\ServiceInterface\Refund\Order\RefundServiceInterface;
use App\ServiceInterface\Payment\Order\PaymentGatewayInterface;

final class OrderRefundService implements RefundServiceInterface
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private OrderRefundTransactionRepositoryInterface $refunds,
    ) {
    }

    public function refund(string $orderId, string $amount, ?string $reason = null): OrderRefundTransaction
    {
        if (!method_exists($this->gateway, 'refund')) {
            throw new \RuntimeException('Gateway does not support refunds');
        }
        $refundId = $this->gateway->refund($orderId, $amount, ['reason' => $reason]);
        $tx = new OrderRefundTransaction($orderId, $amount, $refundId, $reason);
        $this->refunds->add($tx);

        return $tx;
    }
}
