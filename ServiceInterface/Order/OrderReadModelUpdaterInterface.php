<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

interface OrderReadModelUpdaterInterface
{
    public function __construct(
        EntityManagerInterface $em,
        OrderPaymentTransactionRepositoryInterface $payments,
        OrderRefundTransactionRepositoryInterface $refunds,
    );

    public function recalc(string $orderId, string $grandTotal): void;
}
