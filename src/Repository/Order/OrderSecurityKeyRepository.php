<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderSecurityKeyEntity;
use App\Ordering\RepositoryInterface\Order\OrderSecurityKeyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderSecurityKeyRepository extends ServiceEntityRepository implements OrderSecurityKeyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSecurityKeyEntity::class);
    }
}
