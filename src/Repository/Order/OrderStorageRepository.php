<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderStorageEntity;
use App\RepositoryInterface\Order\OrderStorageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderStorageRepository extends ServiceEntityRepository implements OrderStorageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStorageEntity::class);
    }
}
