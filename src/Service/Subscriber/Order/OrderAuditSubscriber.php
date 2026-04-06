<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Entity\Order\OrderAuditLog;
use App\Entity\Order\OrderEventRecord;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use App\ServiceInterface\Subscriber\Order\OrderAuditSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Uid\Uuid;

final readonly class OrderAuditSubscriber implements EventSubscriberInterface, OrderAuditSubscriberInterface
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

        $name = $event::class;
        $payload = $this->normalizeEvent($event);
        $occurredAt = $this->extractDate($event, ['occurredAt', 'getOccurredAt']) ?? new \DateTimeImmutable();

        $record = new OrderEventRecord($eventId, $orderId, $name, $payload, $occurredAt);
        $this->repo->save($record);

        $action = (new \ReflectionClass($event))->getShortName();
        $audit = new OrderAuditLog(Uuid::v7()->toRfc4122(), $orderId, $action, json_encode($payload, JSON_UNESCAPED_SLASHES));
        $this->em->persist($audit);
    }

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

    private function normalizeEvent(object $event): array
    {
        if (method_exists($event, 'toArray')) {
            $data = $event->toArray();

            return is_array($data) ? $data : ['value' => $data];
        }

        $data = [];
        foreach (get_object_vars($event) as $key => $value) {
            $data[$key] = $this->normalizeValue($value);
        }

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
