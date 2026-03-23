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

final class OrderRecalculateController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'not_found'], 404);
        }

        return new JsonResponse(['status' => 'recalculated', 'orderId' => $id], 200);
    }
}
