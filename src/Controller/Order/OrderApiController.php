<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Entity\Order\OutboxMessage;
use App\Service\Order\IdempotencyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class OrderApiController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly IdempotencyService $idem,
    ) {
    }

    #[Route(path: '/orders/{id}/pay', name: 'order_pay', methods: ['POST'])]
    public function pay(string $id, Request $request): JsonResponse
    {
        $key = 'pay:'.$id.':'.($request->headers->get('Idempotency-Key') ?? '');
        if (!$this->idem->checkAndStore($key)) {
            return new JsonResponse(['status' => 'duplicate'], 200);
        }

        $payload = json_encode(['type' => 'OrderPaidEvent', 'data' => ['order_id' => $id]], JSON_THROW_ON_ERROR);
        $this->em->persist(new OutboxMessage($id, 'OrderPaidEvent', $payload));
        $this->em->flush();

        return new JsonResponse(['status' => 'queued']);
    }
}
