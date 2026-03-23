<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Entity\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class OrderCancelController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route(path: '/api/orders/{id}/cancel', name: 'api_order_cancel', methods: ['POST'])]
    public function __invoke(int $id): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'not_found'], 404);
        }
        if (method_exists($order, 'setStatus')) {
            $order->setStatus('cancelled');
            $this->em->flush();
        }

        return new JsonResponse(['status' => 'cancelled'], 200);
    }
}
