<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Controller\Order;

use App\ControllerInterface\Order\DlqControllerInterface;
use App\ServiceInterface\Order\Outbox\DlqServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/order/dlq')]
class DlqController extends AbstractController implements DlqControllerInterface
{
    public function __construct(private readonly DlqServiceInterface $service)
    {
    }

    #[Route(path: '', name: 'order_dlq_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $topic = $request->query->get('topic');
        $q = $request->query->get('q');
        $limit = max(1, min(200, (int) $request->query->get('limit', 50)));
        $offset = max(0, (int) $request->query->get('offset', 0));

        [$items, $total] = $this->service->getPage($topic ?: null, $q ?: null, $limit, $offset);
        $data = [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'items' => array_map(function ($m) {
                return [
                    'id' => method_exists($m, 'getId') ? (string) $m->getId() : null,
                    'topic' => method_exists($m, 'getTopic') ? $m->getTopic() : null,
                    'attempt' => method_exists($m, 'getAttempt') ? $m->getAttempt() : null,
                    'status' => method_exists($m, 'getStatus') ? $m->getStatus() : null,
                    'reason' => method_exists($m, 'getHeader') ? $m->getHeader() : null,
                    'occurredAt' => method_exists($m, 'getOccurredAt') && $m->getOccurredAt() ? $m->getOccurredAt()->format(DATE_ATOM) : null,
                    'availableAt' => method_exists($m, 'getAvailableAt') && $m->getAvailableAt() ? $m->getAvailableAt()->format(DATE_ATOM) : null,
                ];
            }, $items),
        ];

        return new JsonResponse($data);
    }

    #[IsGranted('ROLE_ORDER_ADMIN')]
    #[Route(path: '/{id}/requeue', name: 'order_dlq_requeue_one', methods: ['POST'])]
    public function requeueOne(Request $request, string $id): JsonResponse
    {
        $reset = filter_var($request->query->get('resetAttempt', '1'), FILTER_VALIDATE_BOOLEAN);
        $ok = $this->service->requeueOne($id, $reset);

        return new JsonResponse(['requeued' => $ok]);
    }

    #[IsGranted('ROLE_ORDER_ADMIN')]
    #[Route(path: '/requeue', name: 'order_dlq_requeue_batch', methods: ['POST'])]
    public function requeueBatch(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '[]', true) ?: [];
        $ids = isset($payload['ids']) && is_array($payload['ids']) ? $payload['ids'] : [];
        $reset = isset($payload['resetAttempt']) ? (bool) $payload['resetAttempt'] : true;
        $n = $this->service->requeueMany($ids, $reset);

        return new JsonResponse(['requeued' => $n]);
    }
}
