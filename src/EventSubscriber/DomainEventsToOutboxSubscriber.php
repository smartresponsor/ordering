<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Contract\Domain\RecordsDomainEvents;
use App\Service\Outbox\OutboxWriter;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;

final class DomainEventsToOutboxSubscriber implements EventSubscriber
{
    private array $buffer = [];

    public function __construct(private readonly OutboxWriter $outbox)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::onFlush, Events::postFlush];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if ($entity instanceof RecordsDomainEvents) {
                foreach ($entity->releaseEvents() as $event) {
                    $topic = $event::class;
                    $payload = get_object_vars($event);
                    $this->buffer[] = ['topic' => $topic, 'payload' => $payload];
                }
            }
        }
    }

    /**
     * @throws \JsonException
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        if (!$this->buffer) {
            return;
        }
        $em = $args->getObjectManager();

        foreach ($this->buffer as $e) {
            $this->outbox->store($e['topic'], $e['payload']);
        }
        $this->buffer = [];

        $em->flush();
    }
}
