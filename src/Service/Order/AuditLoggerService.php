<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\Order;
use App\Entity\Order\OrderAuditLog;
use App\ServiceInterface\Order\AuditLoggerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AuditLoggerService implements AuditLoggerServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function logEvent(Order $order, string $event, array $context = [], ?string $actor = null, ?string $ip = null): void
    {
        $log = new OrderAuditLog($order, $event, $actor, $context, $ip);
        $this->em->wrapInTransaction(function () use ($log) {
            $this->em->persist($log);
            $this->em->flush();
        });
    }
}
