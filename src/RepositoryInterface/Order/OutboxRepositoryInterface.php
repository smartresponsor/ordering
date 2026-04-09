<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OutboxMessage;

interface OutboxRepositoryInterface
{
    public function add(OutboxMessage $message): void;

    /** @return iterable<OutboxMessage> */
    public function pullPending(int $limit): iterable;

    public function markSent(OutboxMessage $message): void;

    public function markFailed(OutboxMessage $message, int $delaySeconds = 0): void;
}
