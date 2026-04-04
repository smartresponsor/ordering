<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\IdempotencyStoreInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

use App\ServiceInterface\Order\WorkerIdempotencyGuardInterface;

final class WorkerIdempotencyGuard implements WorkerIdempotencyGuardInterface
{
    public function __construct(
        private IdempotencyKeyPolicy $policy,
        private IdempotencyStoreInterface $store,
    ) {
    }

    public function allow(string $topic, string $payload): bool
    {
        $key = $this->policy->keyForWorker($topic, $payload);

        return $this->store->setIfAbsent($key, $this->policy->ttlSeconds());
    }
}
