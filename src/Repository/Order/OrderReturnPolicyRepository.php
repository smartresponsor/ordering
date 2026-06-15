<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderReturnPolicyEntity;
use App\RepositoryInterface\Order\OrderReturnPolicyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderReturnPolicyRepository extends ServiceEntityRepository implements OrderReturnPolicyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderReturnPolicyEntity::class);
    }
}
