<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderArchiveEntity;
use App\RepositoryInterface\Order\OrderArchiveRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderArchiveRepository extends ServiceEntityRepository implements OrderArchiveRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderArchiveEntity::class);
    }
}
