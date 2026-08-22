<?php

declare(strict_types=1);

namespace App\Ordering\Service\Outbox\Order;

use App\Ordering\ServiceInterface\Outbox\Order\DlqServiceInterface;

final class DlqService implements DlqServiceInterface
{
    public function getPage(?string $topic, ?string $query, int $limit, int $offset): array
    {
        return [[], 0];
    }

    public function requeueOne(string $id, bool $resetAttempt = true): bool
    {
        return true;
    }

    public function requeueMany(array $ids, bool $resetAttempt = true): int
    {
        return count($ids);
    }
}
