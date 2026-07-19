<?php

declare(strict_types=1);

namespace App\Ordering\RepositoryInterface\Order;

use App\Ordering\Entity\Order\OrderEventRecordEntity;

interface OrderEventRepositoryInterface
{
    public function existsByEventId(string $eventId): bool;

    public function save(OrderEventRecordEntity $record): void;

    /** @return list<OrderEventRecordEntity> */
    public function findByOrderId(string $orderId): array;
}
