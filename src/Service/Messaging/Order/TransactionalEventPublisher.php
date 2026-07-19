<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Messaging\Order;

use App\Message\Domain\Order\OrderDomainMessage;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\RepositoryInterface\Order\OutboxRepositoryInterface;
use App\ServiceInterface\Messaging\Order\TransactionalEventPublisherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final readonly class TransactionalEventPublisher implements TransactionalEventPublisherInterface
{
    public function __construct(
        private OutboxRepositoryInterface $outbox,
        private MessageBusInterface $bus,
    ) {
    }

    public function publish(string $topic, array $payload): string
    {
        $messageId = Uuid::v7()->toRfc4122();
        // Пишем в outbox (транзакция с UoW)
        $this->outbox->add(new OrderOutboxMessageEntity($messageId, $topic, $payload));

        // Асинхронная публикация произойдёт через OutboxRelay (ниже)
        return $messageId;
    }

    public function relay(OrderOutboxMessageEntity $m): void
    {
        $this->bus->dispatch(new OrderDomainMessage($m->messageId(), $m->topic(), $m->payload()));
    }
}
