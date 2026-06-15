<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Refund\Order;

use App\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Entity\Order\OrderRefundTransactionEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface RefundProcessorInterface
{
    public function __construct(
        EntityManagerInterface $em,
        MessageBusInterface $bus,
        OrderPaymentGatewayInterface $gateway,
    );

    public function startRefund(OrderRefundTransactionEntity $tx): void;
}
