<?php

declare(strict_types=1);

namespace App\Service\Order\Outbox;

use App\ServiceInterface\Order\Outbox\DlqServiceInterface;

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
