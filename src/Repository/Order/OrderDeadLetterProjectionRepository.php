<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderDeadLetterProjectionEntity;
use App\RepositoryInterface\Order\OrderDeadLetterProjectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderDeadLetterProjectionRepository extends ServiceEntityRepository implements OrderDeadLetterProjectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDeadLetterProjectionEntity::class);
    }
}
