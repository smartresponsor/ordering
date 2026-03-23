<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\Billing\WebhookHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class PaymentWebhookController
{
    public function __construct(private readonly WebhookHandler $handler)
    {
    }

    #[Route(path: '/api/webhooks/payment', name: 'order_payment_webhook', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->handler->handlePayment($request);

        return new JsonResponse($data, 200);
    }

    #[Route(path: '/api/webhooks/refund', name: 'order_refund_webhook', methods: ['POST'])]
    public function refund(Request $request): JsonResponse
    {
        $data = $this->handler->handleRefund($request);

        return new JsonResponse($data, 200);
    }
}
