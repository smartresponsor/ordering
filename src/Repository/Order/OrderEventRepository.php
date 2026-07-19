<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderEventRecordEntity;
use App\Ordering\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderEventRepository implements OrderEventRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function existsByEventId(string $eventId): bool
    {
        return null !== $this->em->find(OrderEventRecordEntity::class, $eventId);
    }

    public function save(OrderEventRecordEntity $record): void
    {
        $this->em->persist($record);
    }

    public function findByOrderId(string $orderId): array
    {
        $records = $this->em->getRepository(OrderEventRecordEntity::class)->findBy(['orderId' => $orderId], ['occurredAt' => 'ASC']);

        return array_values(array_filter($records, static fn (mixed $record): bool => $record instanceof OrderEventRecordEntity));
    }
}
