<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Refund\Order;

use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use App\ServiceInterface\Payment\Order\PaymentGatewayInterface;
use App\ServiceInterface\Refund\Order\RefundServiceInterface;

final readonly class OrderRefundService implements RefundServiceInterface
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private OrderRefundTransactionRepositoryInterface $refunds,
    ) {
    }

    public function refund(string $orderId, string $amount, ?string $reason = null): OrderRefundTransactionEntity
    {
        if (!method_exists($this->gateway, 'refund')) {
            throw new \RuntimeException('Gateway does not support refunds');
        }
        $refundId = $this->gateway->refund($orderId, $amount, ['reason' => $reason]);
        $tx = new OrderRefundTransactionEntity($orderId, $amount, $refundId, $reason);
        $this->refunds->add($tx);

        return $tx;
    }
}
