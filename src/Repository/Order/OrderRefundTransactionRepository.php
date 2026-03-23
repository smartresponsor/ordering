<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRefundTransactionRepository implements OrderRefundTransactionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function add(OrderRefundTransaction $tx): void
    {
        $this->em->persist($tx);
    }

    public function findByOrder(string $orderId): iterable
    {
        return $this->em->getRepository(OrderRefundTransaction::class)->findBy(['orderId' => $orderId], ['id' => 'ASC']);
    }

    public function sumByOrder(string $orderId): string
    {
        $qb = $this->em->createQueryBuilder()
            ->select('COALESCE(SUM(t.amount), 0)')
            ->from(OrderRefundTransaction::class, 't')
            ->where('t.orderId = :o')
            ->setParameter('o', $orderId);
        $sum = $qb->getQuery()->getSingleScalarResult();

        return (string) $sum;
    }
}
