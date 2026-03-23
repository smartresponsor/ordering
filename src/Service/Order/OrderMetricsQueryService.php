<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\OrderMetricsQueryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderMetricsQueryService implements OrderMetricsQueryServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @return array<int,array{date:string,orders:int,gross:string,refund:string,net:string}> */
    public function ordersByDay(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('m')
            ->from(OrderMetricsProjection::class, 'm')
            ->where('m.date BETWEEN :f AND :t')
            ->setParameter('f', $from->format('Y-m-d'))
            ->setParameter('t', $to->format('Y-m-d'))
            ->orderBy('m.date', 'ASC');

        $rows = [];
        foreach ($qb->getQuery()->getResult() as $m) {
            $rows[] = [
                'date' => $m->getDate()->format('Y-m-d'),
                'orders' => $m->getOrdersCount(),
                'gross' => $m->getGrossTotal(),
                'refund' => $m->getRefundTotal(),
                'net' => $m->getNetTotal(),
            ];
        }

        return $rows;
    }
}
