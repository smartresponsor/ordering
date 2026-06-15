<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderLogEntity;
use App\RepositoryInterface\Order\OrderLogRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderLogRepository extends ServiceEntityRepository implements OrderLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLogEntity::class);
    }
}
