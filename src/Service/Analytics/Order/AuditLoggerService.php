<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Analytics\Order;

use App\Entity\Order;
use App\Entity\Order\OrderAuditLog;
use App\ServiceInterface\Analytics\Order\AuditLoggerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class AuditLoggerService implements AuditLoggerServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function logEvent(Order $order, string $event, array $context = [], ?string $actor = null, ?string $ip = null): void
    {
        $payload = $context;
        if (null !== $actor) {
            $payload['actor'] = $actor;
        }
        if (null !== $ip) {
            $payload['ip'] = $ip;
        }

        $log = new OrderAuditLog(
            Uuid::v7()->toRfc4122(),
            $order->getId(),
            $event,
            json_encode($payload, JSON_THROW_ON_ERROR),
        );
        $this->em->wrapInTransaction(function () use ($log): void {
            $this->em->persist($log);
            $this->em->flush();
        });
    }
}
