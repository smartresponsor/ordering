<?php

declare(strict_types=1);

namespace App\Repository\Outbox;

use App\Entity\Order\OrderOutboxMessageEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderOutboxMessageEntity>
 */
final class OutboxMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderOutboxMessageEntity::class);
    }

    /** @return iterable<OrderOutboxMessageEntity> */
    public function findUnpublishedBatch(int $limit = 100): iterable
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.dispatched = false')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->toIterable();
    }
}
