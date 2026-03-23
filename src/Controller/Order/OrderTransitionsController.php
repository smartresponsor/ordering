<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Repository\Order\OrderRepository;
use App\Service\Order\OrderStatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class OrderTransitionsController
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly OrderStatusService $status,
    ) {
    }

    #[Route(path: '/orders/{id}/pay', name: 'order_pay', methods: ['POST'])]
    public function pay(string $id): JsonResponse
    {
        $order = $this->orders->findById($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }
        $this->status->applyTransition($order, 'pay');

        return new JsonResponse(['status' => $order->getStatus()]);
    }

    #[Route(path: '/orders/{id}/cancel', name: 'order_cancel', methods: ['POST'])]
    public function cancel(string $id): JsonResponse
    {
        $order = $this->orders->findById($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }
        $this->status->applyTransition($order, 'cancel');

        return new JsonResponse(['status' => $order->getStatus()]);
    }

    #[Route(path: '/orders/{id}/refund', name: 'order_refund', methods: ['POST'])]
    public function refund(string $id, Request $request): JsonResponse
    {
        $order = $this->orders->findById($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }
        $this->status->applyTransition($order, 'refund');

        return new JsonResponse(['status' => $order->getStatus()]);
    }
}
