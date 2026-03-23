<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OutboxMessage;

interface OutboxRepositoryInterface
{
    public function add(OutboxMessage $message): void;

    public function pullPending(int $limit = 50): iterable;

    public function markSent(OutboxMessage $message): void;

    public function markFailed(OutboxMessage $message, ?int $retryAfterSec = null): void;
}
