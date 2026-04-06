<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Entity\Order;
use App\Service\Workflow\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class OrderPayController
{
    public function __construct(private EntityManagerInterface $em, private OrderWorkflowService $wf)
    {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $order = $this->em->find(Order::class, $id);
        if (!$order instanceof Order) {
            return new JsonResponse(['message' => 'Order not found'], 404);
        }
        $payload = $request->toArray();
        $amount = (int) ($payload['amount'] ?? 0);
        $this->wf->pay($order, $amount);

        return new JsonResponse(['status' => $order->getStatus()]);
    }
}
