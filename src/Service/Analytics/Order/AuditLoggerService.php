<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Analytics\Order;

use App\Entity\Order;
use App\Entity\Order\OrderAuditLogEntity;
use App\ServiceInterface\Analytics\Order\AuditLoggerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AuditLoggerService implements AuditLoggerServiceInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function logEvent(Order $order, string $event, array $context = [], ?string $actor = null, ?string $ip = null): void
    {
        $payload = $context;
        if (null !== $actor) {
            $payload['actor'] = $actor;
        }
        if (null !== $ip) {
            $payload['ip'] = $ip;
        }

        $log = new OrderAuditLogEntity(
            $event,
            $payload,
            $order->getId(),
            null !== $actor ? 'user' : 'system',
            $actor,
            'order',
            $ip,
        );
        $this->em->wrapInTransaction(function () use ($log): void {
            $this->em->persist($log);
            $this->em->flush();
        });
    }
}
