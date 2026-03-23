<?php

declare(strict_types=1);

namespace App\Repository\Outbox;

use App\Entity\Outbox\OutboxMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OutboxMessage>
 */
final class OutboxMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutboxMessage::class);
    }

    /** @return iterable<OutboxMessage> */
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
