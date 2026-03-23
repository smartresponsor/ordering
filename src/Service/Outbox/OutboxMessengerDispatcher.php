<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Entity\Outbox\OutboxMessage;
use App\Message\OrderEventMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxMessengerDispatcher
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     * @throws \JsonException
     */
    public function dispatchPending(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OutboxMessage::class);
        $messages = $repo->findBy(['dispatched' => false], ['id' => 'ASC'], $limit);
        $count = 0;

        foreach ($messages as $message) {
            $payload = json_decode($message->getPayload(), true, 512, JSON_THROW_ON_ERROR);
            $orderId = (int) ($payload['orderId'] ?? $payload['aggregateId'] ?? 0);
            $this->bus->dispatch(new OrderEventMessage($message->getEventType(), $orderId));
            $message->markDispatched();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }
}
