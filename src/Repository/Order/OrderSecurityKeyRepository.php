<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderSecurityKeyEntity;
use App\RepositoryInterface\Order\OrderSecurityKeyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderSecurityKeyRepository extends ServiceEntityRepository implements OrderSecurityKeyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSecurityKeyEntity::class);
    }
}
