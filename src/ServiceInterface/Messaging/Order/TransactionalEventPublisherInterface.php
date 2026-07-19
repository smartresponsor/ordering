<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Messaging\Order;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\RepositoryInterface\Order\OutboxRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface TransactionalEventPublisherInterface
{
    public function __construct(
        OutboxRepositoryInterface $outbox,
        MessageBusInterface $bus,
    );

    public function publish(string $topic, array $payload): string;

    public function relay(OrderOutboxMessageEntity $m): void;
}
