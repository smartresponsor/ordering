<?php

declare(strict_types=1);

namespace App\ReadModel\Repository;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderReadRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findOrderById(string $id): ?Order
    {
        /** @var Order|null $order */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $id]);

        return $order;
    }

    /** @return array<int, Order> */
    public function findByCustomerId(string $customerId): array
    {
        return $this->entityManager->getRepository(Order::class)->findBy(['customerId' => $customerId]);
    }
}
