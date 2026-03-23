<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\OrderPaymentService;
use App\Service\Order\WebhookIdempotencyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class WebhookController
{
    public function __construct(
        private readonly WebhookIdempotencyService $idem,
        private readonly OrderPaymentService $payments,
    ) {
    }

    #[Route(path: '/webhook/payment', name: 'payment_webhook', methods: ['POST'])]
    public function payment(Request $request): JsonResponse
    {
        $key = $request->headers->get('Idempotency-Key') ?? '';
        $payload = $request->getContent() ?: '{}';
        $data = json_decode($payload, true) ?: [];
        $event = $data['type'] ?? 'unknown';
        $orderId = $data['orderId'] ?? '';
        $amount = $data['amount'] ?? '0.00';
        $currency = $data['currency'] ?? 'USD';
        $paymentId = $data['paymentId'] ?? 'webhook';

        $accepted = $this->idem->handleOnce($key, (string) $event, $payload, function () use ($orderId, $paymentId, $currency, $amount) {
            $this->payments->applyPartialPayment($orderId, $paymentId, $currency, (string) $amount);
        });

        return new JsonResponse(['accepted' => $accepted]);
    }
}
