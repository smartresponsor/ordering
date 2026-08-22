<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use App\Ordering\Message\Domain\Order\OrderDomainMessage;
use App\Ordering\RepositoryInterface\Order\OutboxRepositoryInterface;
use App\Ordering\ServiceInterface\Security\Order\TransactionalEventPublisherInterface;
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
        // РџРёС€РµРј РІ outbox (С‚СЂР°РЅР·Р°РєС†РёСЏ СЃ UoW)
        $this->outbox->add(new OrderOutboxMessageEntity($messageId, $topic, $payload));

        // РђСЃРёРЅС…СЂРѕРЅРЅР°СЏ РїСѓР±Р»РёРєР°С†РёСЏ РїСЂРѕРёР·РѕР№РґС‘С‚ С‡РµСЂРµР· OutboxRelay (РЅРёР¶Рµ)
        return $messageId;
    }

    public function relay(OrderOutboxMessageEntity $m): void
    {
        $this->bus->dispatch(new OrderDomainMessage($m->messageId(), $m->topic(), $m->payload()));
    }
}
