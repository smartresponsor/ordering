<?php

declare(strict_types=1);

namespace App\Ordering\Controller\Api;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
use App\Ordering\Service\Workflow\Order\OrderWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class OrderShipController
{
    public function __construct(private EntityManagerInterface $em, private OrderWorkflowService $wf)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($id);
        if (!$order instanceof OrderEntity) {
            return new JsonResponse(['message' => 'Order not found'], 404);
        }
        $this->wf->ship($order);

        return new JsonResponse(['status' => $order->getStatus()]);
    }
}
