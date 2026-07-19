<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Message\Outbox\OrderOutboxDispatchedMessage;
use App\Ordering\Entity\Order\OrderOutboxMessageEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxMessengerDispatcher
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws \JsonException
     */
    public function dispatchPending(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OrderOutboxMessageEntity::class);
        $messages = $repo->findBy(['dispatched' => false], ['id' => 'ASC'], $limit);
        $count = 0;

        foreach ($messages as $message) {
            $payload = $message->payload();
            $this->bus->dispatch(new OrderOutboxDispatchedMessage($message->getEventType(), $payload));
            $message->markDispatched();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }
}
