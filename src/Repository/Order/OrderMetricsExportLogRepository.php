<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderMetricsExportLogEntity;
use App\Ordering\RepositoryInterface\Order\OrderMetricsExportLogRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderMetricsExportLogRepository extends ServiceEntityRepository implements OrderMetricsExportLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMetricsExportLogEntity::class);
    }
}
