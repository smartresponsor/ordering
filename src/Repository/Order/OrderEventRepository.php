<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\OrderEventRecord;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderEventRepository implements OrderEventRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(OrderEventRecord $record): void
    {
        $this->em->persist($record);
    }

    public function findByOrder(string $orderId, int $limit = 100, int $offset = 0): iterable
    {
        return $this->em->getRepository(OrderEventRecord::class)->findBy(
            ['orderId' => $orderId],
            ['occurredAt' => 'ASC'],
            $limit,
            $offset
        );
    }

    public function findLastByOrder(string $orderId): ?OrderEventRecord
    {
        return $this->em->getRepository(OrderEventRecord::class)->findOneBy(
            ['orderId' => $orderId],
            ['occurredAt' => 'DESC']
        );
    }

    public function existsByEventId(string $eventId): bool
    {
        return (bool) $this->em->getRepository(OrderEventRecord::class)->findOneBy(['eventId' => $eventId]);
    }
}
