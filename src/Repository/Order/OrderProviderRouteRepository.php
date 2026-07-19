<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderProviderRouteEntity;
use App\Ordering\RepositoryInterface\Order\OrderProviderRouteRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderProviderRouteRepository extends ServiceEntityRepository implements OrderProviderRouteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProviderRouteEntity::class);
    }
}
