<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Webhook\Order;

use Symfony\Component\HttpFoundation\Request;

interface WebhookHandlerInterface
{
    /**
     * @return array<string, string>
     */
    public function handlePayment(Request $request): array;

    /**
     * @return array<string, string>
     */
    public function handleRefund(Request $request): array;
}
