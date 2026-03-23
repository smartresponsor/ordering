<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\OrderMetricsQueryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class OrderMetricsController
{
    public function __construct(private readonly OrderMetricsQueryService $query)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $from = new \DateTimeImmutable($request->query->get('from', 'first day of this month'));
        $to = new \DateTimeImmutable($request->query->get('to', 'now'));

        return new JsonResponse($this->query->ordersByDay($from, $to));
    }
}
