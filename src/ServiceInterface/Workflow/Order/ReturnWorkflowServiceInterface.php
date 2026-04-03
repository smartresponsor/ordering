<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Workflow\Order;

use App\Entity\Order\OrderReturnRequest;
use App\Service\Order\RefundProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface ReturnWorkflowServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        RefundProcessor $refund,
        MessageBusInterface $bus,
    );

    public function createReturnAndRefund(string $orderId, int $amountMinor, string $currency, ?string $reason = null): OrderReturnRequest;
}
