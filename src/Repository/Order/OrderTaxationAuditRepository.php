<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderTaxationAuditEntity;
use App\Ordering\RepositoryInterface\Order\OrderTaxationAuditRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderTaxationAuditRepository extends ServiceEntityRepository implements OrderTaxationAuditRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderTaxationAuditEntity::class);
    }
}
