<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Entity\Order\OrderAuditLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class AuditController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $orderId = (int) $request->query->get('orderId', 0);
        $qb = $this->em->createQueryBuilder()
            ->select('a')
            ->from(OrderAuditLog::class, 'a')
            ->where('a.order = :oid')
            ->setParameter('oid', $orderId)
            ->orderBy('a.createdAt', 'ASC');

        $rows = [];
        foreach ($qb->getQuery()->getResult() as $a) {
            $ref = new \ReflectionClass($a);
            $rows[] = [
                'event' => $ref->getProperty('event')->getValue($a) ?? '',
                'createdAt' => $ref->getProperty('createdAt')->getValue($a)->format('c'),
                'payload' => $ref->getProperty('payload')->getValue($a) ?? [],
                'actor' => $ref->getProperty('actor')->getValue($a) ?? null,
            ];
        }

        return new JsonResponse($rows);
    }
}
