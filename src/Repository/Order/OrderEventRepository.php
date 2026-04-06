<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderEventRecord;
use App\RepositoryInterface\Order\OrderEventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderEventRepository implements OrderEventRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function existsByEventId(string $eventId): bool { return false; }
    public function save(OrderEventRecord $record): void { $this->em->persist($record); }
    public function findByOrderId(string $orderId): array { return []; }
}
