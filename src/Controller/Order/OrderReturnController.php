<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\ReturnWorkflowService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class OrderReturnController
{
    public function __construct(private ReturnWorkflowService $workflow)
    {
    }

    #[Route(path: '/api/orders/{id}/returns', name: 'order_return_create', methods: ['POST'])]
    public function create(string $id, Request $req): JsonResponse
    {
        $data = json_decode($req->getContent() ?: '{}', true) ?? [];
        $amount = (int) ($data['amountMinor'] ?? 0);
        $currency = (string) ($data['currency'] ?? 'USD');
        $reason = $data['reason'] ?? null;

        $ret = $this->workflow->createReturnAndRefund($id, $amount, $currency, $reason);

        return new JsonResponse(['returnRequestId' => $ret->id(), 'status' => $ret->status()]);
    }
}
