<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Entity\Order\OrderAuditLogEntity;
use App\Entity\Order\OrderEventRecordEntity;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Uid\Uuid;

final readonly class OrderAuditSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em, private OrderEventRepositoryInterface $repo)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'order.placed' => 'onAny',
            'order.paid' => 'onAny',
            'order.shipped' => 'onAny',
            'order.cancelled' => 'onAny',
            'order.refunded' => 'onAny',
        ];
    }

    public function onAny(object $event): void
    {
        $orderId = $this->extract($event, ['orderId', 'getOrderId']);
        if (null === $orderId) {
            return;
        }

        $eventId = $this->extract($event, ['eventId', 'getEventId']) ?? Uuid::v7()->toRfc4122();
        if ($this->repo->existsByEventId($eventId)) {
            return;
        }

        $nameEntity = $event::class;
        $payload = $this->normalizeEvent($event);
        $occurredAt = $this->extractDate($event, ['occurredAt', 'getOccurredAt']) ?? new \DateTimeImmutable();

        $record = new OrderEventRecordEntity($eventId, $orderId, $nameEntity, $payload, $occurredAt);
        $this->repo->save($record);

        $action = (new \ReflectionClass($event))->getShortName();
        $audit = new OrderAuditLogEntity(
            $action,
            $payload,
            $orderId,
            'event',
            null,
            'order',
            null,
            null,
            null,
            $occurredAt,
        );
        $this->em->persist($audit);
    }

    /**
     * @param array<int, string> $methods
     */
    private function extract(object $event, array $methods): ?string
    {
        foreach ($methods as $method) {
            if (!method_exists($event, $method)) {
                continue;
            }

            $value = $event->$method();
            if (is_scalar($value) && '' !== (string) $value) {
                return (string) $value;
            }
        }

        return null;
    }

    /**
     * @param array<int, string> $methods
     */
    private function extractDate(object $event, array $methods): ?\DateTimeImmutable
    {
        foreach ($methods as $method) {
            if (!method_exists($event, $method)) {
                continue;
            }

            $value = $event->$method();
            if ($value instanceof \DateTimeImmutable) {
                return $value;
            }
            if ($value instanceof \DateTimeInterface) {
                return \DateTimeImmutable::createFromInterface($value);
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeEvent(object $event): array
    {
        if (method_exists($event, 'toArray')) {
            $data = $event->toArray();

            return is_array($data) ? $data : ['value' => $data];
        }

        $data = array_map(function ($value) {
            return $this->normalizeValue($value);
        }, get_object_vars($event));

        return $data;
    }

    private function normalizeValue(mixed $value): mixed
    {
        return match (true) {
            $value instanceof \DateTimeInterface => $value->format(DATE_ATOM),
            is_object($value) => (array) $value,
            default => $value,
        };
    }
}
