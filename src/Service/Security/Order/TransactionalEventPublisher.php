<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\OutboxMessage;
use App\Message\Domain\Order\OrderDomainMessage;
use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use App\ServiceInterface\Security\Order\TransactionalEventPublisherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class TransactionalEventPublisher implements TransactionalEventPublisherInterface
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
        $this->outbox->add(new OutboxMessage($messageId, $topic, $payload));

        // Асинхронная публикация произойдёт через OutboxRelay (ниже)
        return $messageId;
    }

    public function relay(OutboxMessage $m): void
    {
        $this->bus->dispatch(new OrderDomainMessage($m->messageId(), $m->topic(), $m->payload()));
    }
}
