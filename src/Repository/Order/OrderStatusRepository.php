<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStatusEntity;
use App\RepositoryInterface\Order\OrderStatusRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderStatusRepository extends ServiceEntityRepository implements OrderStatusRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatusEntity::class);
    }
}
