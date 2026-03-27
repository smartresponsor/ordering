<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

interface WebhookHandlerInterface
{
    public function __construct(
        EntityManagerInterface $em,
        IdempotencyGuardInterface $guard,
    );

    public function handlePayment(Request $request): array;

    public function handleRefund(Request $request): array;
}
