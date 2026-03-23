<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\IdempotencyKey;
use App\RepositoryInterface\Order\IdempotencyKeyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IdempotencyKeyRepository extends ServiceEntityRepository implements IdempotencyKeyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdempotencyKey::class);
    }

    public function findOne(string $key): ?IdempotencyKey
    {
        return $this->find($key);
    }

    public function save(IdempotencyKey $key): void
    {
        $this->_em->persist($key);
    }

    public function delete(IdempotencyKey $key): void
    {
        $this->_em->remove($key);
    }

    public function purgeExpired(): int
    {
        $qb = $this->createQueryBuilder('k')
            ->delete()
            ->andWhere('k.expireAt < :now')
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->getQuery();

        return $qb->execute();
    }
}
