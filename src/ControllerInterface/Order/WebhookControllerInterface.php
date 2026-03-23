<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

use App\Service\Order\OrderPaymentService;
use App\Service\Order\WebhookIdempotencyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface WebhookControllerInterface
{
    public function __construct(
        WebhookIdempotencyService $idem,
        OrderPaymentService $payments,
    );

    public function payment(Request $request): JsonResponse;
}
