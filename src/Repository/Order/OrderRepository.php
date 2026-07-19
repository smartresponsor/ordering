<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEntity::class);
    }

    public function findByIdentifier(string|int $identifier): ?OrderEntity
    {
        if (is_int($identifier) || ctype_digit((string) $identifier)) {
            $order = $this->find((int) $identifier);
            if ($order instanceof OrderEntity) {
                return $order;
            }
        }

        $order = $this->findOneBy(['slug' => (string) $identifier]);

        return $order instanceof OrderEntity ? $order : null;
    }
}
