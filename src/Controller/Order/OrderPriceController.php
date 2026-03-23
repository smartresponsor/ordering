<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Service\Order\OrderPriceView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class OrderPriceController extends AbstractController
{
    #[Route(path: '/api/orders/{id}/price', name: 'api_order_price', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        $view = new OrderPriceView($id, 10000, 1000, 1800, 10800, 'USD');

        return $this->json($view);
    }
}
