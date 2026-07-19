<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderDeadLetterProjectionEntity;
use App\Ordering\RepositoryInterface\Order\OrderDeadLetterProjectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderDeadLetterProjectionRepository extends ServiceEntityRepository implements OrderDeadLetterProjectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDeadLetterProjectionEntity::class);
    }
}
