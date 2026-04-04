<?php

declare(strict_types=1);

namespace App\ServiceInterface\Outbox\Order;

interface DlqServiceInterface
{
    /** @return array{0: array<int, array<string, mixed>>, 1: int} */
    public function getPage(?string $topic, ?string $query, int $limit, int $offset): array;

    public function requeueOne(string $id, bool $resetAttempt = true): bool;

    /** @param array<int, string> $ids */
    public function requeueMany(array $ids, bool $resetAttempt = true): int;
}
