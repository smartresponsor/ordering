<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderEventRecord;

interface OrderEventRepositoryInterface
{
    public function existsByEventId(string $eventId): bool;
    public function save(OrderEventRecord $record): void;
    /** @return list<OrderEventRecord> */
    public function findByOrderId(string $orderId): array;
}
