<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Entity\Outbox\OutboxMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class OutboxProcessor
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function process(int $limit = 100): int
    {
        $repo = $this->em->getRepository(OutboxMessage::class);
        $messages = $repo->findBy(['dispatched' => false], ['id' => 'ASC'], $limit);
        $count = 0;

        foreach ($messages as $message) {
            $payload = json_decode($message->getPayload(), true, 512, JSON_THROW_ON_ERROR);
            $eventName = $message->getEventType();
            $orderId = (int) ($payload['orderId'] ?? $payload['aggregateId'] ?? 0);
            $event = new class($orderId, $eventName) {
                public function __construct(public int $orderId, public string $class)
                {
                }
            };

            $this->dispatcher->dispatch($event, $eventName);
            $message->markDispatched();
            ++$count;
        }

        $this->em->flush();

        return $count;
    }
}
