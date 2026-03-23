<?php

declare(strict_types=1);

namespace App\Controller\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

use App\Entity\Order;
use App\Service\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class OrderPayController
{
    public function __construct(private EntityManagerInterface $em, private OrderWorkflowService $wf)
    {
    }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        $order = $this->em->find(Order::class, $id);
        if (!$order) {
            return new JsonResponse(['message' => 'Order not found'], 404);
        } $amount = (int) ($request->toArray()['amount'] ?? 0);
        $this->wf->pay($order, $amount);

        return new JsonResponse(['status' => $order->getStatus()->value]);
    }
}
