<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Payment\Order;

use App\Entity\OrderPaymentTransaction;
use App\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;

interface PartialPaymentServiceInterface
{
    public function __construct(OrderPaymentTransactionRepositoryInterface $payments);

    public function applyPartial(string $orderId, string $amount, string $method, string $txId): OrderPaymentTransaction;

    public function balance(string $orderId, string $grandTotal, string $refundedTotal = '0.00'): string;
}
