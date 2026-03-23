<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\OrderPaymentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class PaymentController
{
    public function __construct(private readonly OrderPaymentService $service)
    {
    }

    #[Route(path: '/orders/{id}/payments', name: 'order_partial_payment', methods: ['POST'])]
    public function partial(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent() ?: '{}', true) ?: [];
        $paymentId = $data['paymentId'] ?? bin2hex(random_bytes(8));
        $currency = $data['currency'] ?? 'USD';
        $amount = $data['amount'] ?? '0.00';
        $this->service->applyPartialPayment($id, $paymentId, $currency, $amount);

        return new JsonResponse(['status' => 'partial-accepted', 'paymentId' => $paymentId]);
    }

    #[Route(path: '/orders/{id}/refunds', name: 'order_refund_create', methods: ['POST'])]
    public function refund(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent() ?: '{}', true) ?: [];
        $refundId = $data['refundId'] ?? bin2hex(random_bytes(8));
        $currency = $data['currency'] ?? 'USD';
        $amount = $data['amount'] ?? '0.00';
        $reason = $data['reason'] ?? null;
        $this->service->refund($id, $refundId, $currency, $amount, $reason);

        return new JsonResponse(['status' => 'refund-accepted', 'refundId' => $refundId]);
    }
}
