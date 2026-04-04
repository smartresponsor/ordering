<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

interface OrderPaymentReconciliationServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
    );

    public function onCaptured(string $orderId, string $paymentId, int $amountMinor, string $currency): void;

    public function onRefunded(string $orderId, string $paymentId, int $amountMinor, string $currency): void;
}
