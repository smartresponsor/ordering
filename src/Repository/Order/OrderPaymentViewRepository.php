<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderPaymentView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderPaymentView>
 */
final class OrderPaymentViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPaymentView::class);
    }

    /**
     * @return list<OrderPaymentView>
     */
    public function findStaleViews(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.updatedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
