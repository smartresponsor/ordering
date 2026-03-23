<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Repository\Outbox\OutboxMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface OutboxPublisherInterface
{
    public function __construct(
        OutboxMessageRepository $repo,
        EntityManagerInterface $em,
        MessageBusInterface $bus,
    );

    public function replay(int $limit = 100): int;

    public function storeAndPublish(string $aggregateId, string $eventType, array $payload): void;

    public function publish(string $topic, array $payload): void;
}
