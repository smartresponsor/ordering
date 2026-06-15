<?php

declare(strict_types=1);

namespace App\ReadModel\Repository;

use App\Entity\Order\OrderEntity;
use App\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderReadRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findOrderById(string $id): ?OrderEntity
    {
        /** @var OrderRepository $repo */
        $repo = $this->entityManager->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($id);

        return $order;
    }

    /** @return array<int, OrderEntity> */
    public function findByCustomerId(string $customerId): array
    {
        return $this->entityManager->getRepository(OrderEntity::class)->findBy(['customerId' => $customerId]);
    }
}
