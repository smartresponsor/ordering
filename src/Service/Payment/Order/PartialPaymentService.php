<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Ordering\Entity\Order\OrderPaymentTransactionEntity;
use App\Ordering\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use App\ServiceInterface\Payment\Order\PartialPaymentServiceInterface;

final readonly class PartialPaymentService implements PartialPaymentServiceInterface
{
    public function __construct(private OrderPaymentTransactionRepositoryInterface $payments)
    {
    }

    public function applyPartial(string $orderId, string $amount, string $method, string $txId): OrderPaymentTransactionEntity
    {
        $tx = new OrderPaymentTransactionEntity($orderId, $amount, $method);
        $tx->succeed($txId);
        $this->payments->add($tx);

        return $tx;
    }

    public function balance(string $orderId, string $grandTotal, string $refundedTotal = '0.00'): string
    {
        $paid = $this->payments->sumSucceededByOrder($orderId);
        $paidDec = (float) $paid;
        $totalDec = (float) $grandTotal - (float) $refundedTotal;
        $bal = max(0.0, $totalDec - $paidDec);

        return number_format($bal, 2, '.', '');
    }
}
