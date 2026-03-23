<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\OutboxMessage;
use App\RepositoryInterface\Order\OutboxMessageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OutboxMessageRepository extends ServiceEntityRepository implements OutboxMessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutboxMessage::class);
    }

    public function add(OutboxMessage $message): void
    {
        $this->_em->persist($message);
    }

    public function remove(OutboxMessage $message): void
    {
        $this->_em->remove($message);
    }

    public function findReady(int $limit): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.status IN (:s)')
            ->andWhere('m.availableAt <= :now')
            ->setParameter('s', ['new', 'retry'])
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->orderBy('m.occurredAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }

    public function markSent(OutboxMessage $message): void
    {
        $message->setStatus('sent');
        $this->_em->persist($message);
    }

    public function markFailed(OutboxMessage $message, string $reason): void
    {
        $message->setStatus('failed');
        $message->setHeader($reason);
        $this->_em->persist($message);
    }

    public function reschedule(OutboxMessage $message, \DateTimeImmutable $availableAt): void
    {
        $message->setStatus('retry');
        $message->setAvailableAt($availableAt);
        $this->_em->persist($message);
    }

    public function moveToDead(OutboxMessage $message, string $reason): void
    {
        $message->setStatus('dead');
        $message->setHeader($reason);
        $this->_em->persist($message);
    }

    public function findId(string $id): ?OutboxMessage
    {
        return $this->find($id);
    }

    public function findDeadPage(?string $topic, ?string $q, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.status = :dead')
            ->setParameter('dead', 'dead')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('m.occurredAt', 'DESC');

        if ($topic) {
            $qb->andWhere('m.topic = :topic')->setParameter('topic', $topic);
        }
        if ($q) {
            $qb->andWhere('m.payload LIKE :q OR m.header LIKE :q')->setParameter('q', '%'.$q.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function countDead(?string $topic, ?string $q): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.status = :dead')
            ->setParameter('dead', 'dead');

        if ($topic) {
            $qb->andWhere('m.topic = :topic')->setParameter('topic', $topic);
        }
        if ($q) {
            $qb->andWhere('m.payload LIKE :q OR m.header LIKE :q')->setParameter('q', '%'.$q.'%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
