<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OutboxMessage;

interface OutboxMessageRepositoryInterface
{
    public function add(OutboxMessage $message): void;

    public function remove(OutboxMessage $message): void;

    public function findReady(int $limit): array;

    public function markSent(OutboxMessage $message): void;

    public function markFailed(OutboxMessage $message, string $reason): void;

    public function reschedule(OutboxMessage $message, \DateTimeImmutable $availableAt): void;

    public function moveToDead(OutboxMessage $message, string $reason): void;

    /** New helpers for DLQ viewer */
    public function findId(string $id): ?OutboxMessage;

    /** @return OutboxMessage[] */
    public function findDeadPage(?string $topic, ?string $q, int $limit, int $offset): array;

    public function countDead(?string $topic, ?string $q): int;
}
