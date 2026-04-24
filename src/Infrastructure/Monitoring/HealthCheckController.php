<?php

declare(strict_types=1);

namespace App\Infrastructure\Monitoring;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class HealthCheckController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/_health/order', name: 'order_health', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        try {
            $this->em->createQueryBuilder()
                ->select('COUNT(o.id)')
                ->from(Order::class, 'o')
                ->getQuery()
                ->getSingleScalarResult();

            return new JsonResponse(['status' => 'ok', 'db' => true], 200);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => 'fail', 'db' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
