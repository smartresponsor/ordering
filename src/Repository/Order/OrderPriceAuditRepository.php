<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderPriceAuditEntity;
use App\RepositoryInterface\Order\OrderPriceAuditRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderPriceAuditRepository extends ServiceEntityRepository implements OrderPriceAuditRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPriceAuditEntity::class);
    }
}
