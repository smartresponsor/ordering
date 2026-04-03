<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Entity\Order;
use App\Service\Workflow\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class OrderShipController
{
    public function __construct(private EntityManagerInterface $em, private OrderWorkflowService $wf)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $order = $this->em->find(Order::class, $id);
        if (!$order) {
            return new JsonResponse(['message' => 'Order not found'], 404);
        } $this->wf->ship($order);

        return new JsonResponse(['status' => $order->getStatus()->value]);
    }
}
