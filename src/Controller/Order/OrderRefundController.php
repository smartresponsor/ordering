<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Repository\Order\OrderRefundTransactionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class OrderRefundController
{
    public function __construct(private OrderRefundTransactionRepository $repo)
    {
    }

    #[Route(path: '/api/orders/{id}/refunds', name: 'order_refunds_list', methods: ['GET'])]
    public function list(string $id): JsonResponse
    {
        $items = $this->repo->findBy(['orderId' => $id]);
        $out = [];
        foreach ($items as $tx) {
            $out[] = ['id' => $tx->id(), 'status' => $tx->status()];
        }

        return new JsonResponse($out);
    }
}
