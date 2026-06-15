<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderOutboxMessageEntity;

interface OutboxRepositoryInterface
{
    public function add(OrderOutboxMessageEntity $message): void;

    /** @return iterable<OrderOutboxMessageEntity> */
    public function pullPending(int $limit): iterable;

    public function markSent(OrderOutboxMessageEntity $message): void;

    public function markFailed(OrderOutboxMessageEntity $message, int $delaySeconds = 0): void;
}
