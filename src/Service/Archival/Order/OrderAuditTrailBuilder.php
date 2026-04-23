<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Archival\Order;

use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use App\ServiceInterface\Archival\Order\OrderAuditTrail;
use App\ServiceInterface\Archival\Order\OrderAuditTrailBuilderInterface;

final readonly class OrderAuditTrailBuilder implements OrderAuditTrailBuilderInterface
{
    public function __construct(private OrderEventRepositoryInterface $repo)
    {
    }

    public function buildForOrder(string $orderId): OrderAuditTrail
    {
        $events = [];
        foreach ($this->repo->findByOrderId($orderId) as $e) {
            $events[] = [
                'eventId' => $e->eventId(),
                'eventName' => $e->eventName(),
                'occurredAt' => $e->occurredAt()->format(DATE_ATOM),
                'payload' => $e->payload(),
            ];
        }

        return new OrderAuditTrail($orderId, \count($events), $events);
    }
}
