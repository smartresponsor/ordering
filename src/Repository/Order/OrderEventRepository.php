<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderEventRecord;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderEventRepository implements OrderEventRepositoryInterface
{
    /** @var array<string, OrderEventRecord> */
    private static array $records = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function existsByEventId(string $eventId): bool
    {
        if (isset(self::$records[$eventId])) {
            return true;
        }

        try {
            return null !== $this->em->find(OrderEventRecord::class, $eventId);
        } catch (\Throwable) {
            return false;
        }
    }

    public function save(OrderEventRecord $record): void
    {
        self::$records[$record->eventId()] = $record;
        $this->em->persist($record);
    }

    public function findByOrderId(string $orderId): array
    {
        $records = array_values(array_filter(
            self::$records,
            static fn (OrderEventRecord $record): bool => $record->orderId() === $orderId,
        ));

        usort(
            $records,
            static fn (OrderEventRecord $left, OrderEventRecord $right): int => $left->occurredAt() <=> $right->occurredAt(),
        );

        return $records;
    }
}
