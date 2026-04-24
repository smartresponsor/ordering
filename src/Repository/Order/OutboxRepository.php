<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Outbox\OutboxMessage;
use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OutboxRepository implements OutboxRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OutboxMessage $message): void
    {
        $this->em->persist($message);
    }

    public function pullPending(int $limit): iterable
    {
        return $this->em->getRepository(OutboxMessage::class)
            ->createQueryBuilder('m')
            ->andWhere('m.dispatched = false')
            ->andWhere('m.availableAt IS NULL OR m.availableAt <= :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('m.occurredAt', 'ASC')
            ->setMaxResults(max(0, $limit))
            ->getQuery()
            ->toIterable();
    }

    public function markSent(OutboxMessage $message): void
    {
        $message->markSent();
    }

    public function markFailed(OutboxMessage $message, int $delaySeconds = 0): void
    {
        $message->markFailed($delaySeconds);
    }
}
