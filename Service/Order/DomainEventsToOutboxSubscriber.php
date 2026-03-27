<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Contract\Domain\RecordsDomainEvents;
use App\Service\Outbox\OutboxWriter;
use App\ServiceInterface\Order\DomainEventsToOutboxSubscriberInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;

final class DomainEventsToOutboxSubscriber implements EventSubscriber, DomainEventsToOutboxSubscriberInterface
{
    /** @var list<array{topic:string,payload:array}> */
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
            $uow->getScheduledEntityUpdates(),
        );

        foreach ($entities as $entity) {
            if (!$entity instanceof RecordsDomainEvents) {
                continue;
            }

            foreach ($entity->releaseEvents() as $event) {
                $this->buffer[] = [
                    'topic' => $event::class,
                    'payload' => get_object_vars($event),
                ];
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if ([] === $this->buffer) {
            return;
        }

        $em = $args->getObjectManager();

        foreach ($this->buffer as $event) {
            $this->outbox->store($event['topic'], $event['payload']);
        }

        $this->buffer = [];
        $em->flush();
    }
}
