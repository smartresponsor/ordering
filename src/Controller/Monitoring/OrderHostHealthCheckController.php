<?php

declare(strict_types=1);

namespace App\Ordering\Controller\Monitoring;

use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Routing\Attribute\Route;

final readonly class OrderHostHealthCheckController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TransportInterface $asyncTransport,
    ) {
    }

    #[Route('/healthz', name: 'healthz', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        try {
            $this->em->createQueryBuilder()
                ->select('COUNT(o.id)')
                ->from(OrderEntity::class, 'o')
                ->getQuery()
                ->getSingleScalarResult();

            $rabbitOk = method_exists($this->asyncTransport, 'get') || method_exists($this->asyncTransport, '__toString');

            return new JsonResponse(['status' => 'ok', 'rabbitmq' => $rabbitOk], 200);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => 'fail', 'error' => $e->getMessage()], 500);
        }
    }
}
