<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderEventRecord;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderEventRepository implements OrderEventRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function existsByEventId(string $eventId): bool
    {
        return null !== $this->em->find(OrderEventRecord::class, $eventId);
    }

    public function save(OrderEventRecord $record): void
    {
        $this->em->persist($record);
    }

    public function findByOrderId(string $orderId): array
    {
        $records = $this->em->getRepository(OrderEventRecord::class)->findBy(['orderId' => $orderId], ['occurredAt' => 'ASC']);

        return array_values(array_filter($records, static fn (mixed $record): bool => $record instanceof OrderEventRecord));
    }
}
