<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderMetricsExportLogEntity;
use App\RepositoryInterface\Order\OrderMetricsExportLogRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderMetricsExportLogRepository extends ServiceEntityRepository implements OrderMetricsExportLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMetricsExportLogEntity::class);
    }
}
