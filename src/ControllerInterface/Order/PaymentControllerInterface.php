<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

use App\Service\Order\OrderPaymentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface PaymentControllerInterface
{
    public function __construct(OrderPaymentService $service);

    public function partial(string $id, Request $request): JsonResponse;

    public function refund(string $id, Request $request): JsonResponse;
}
