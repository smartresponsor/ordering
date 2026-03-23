<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

use App\Entity\Order\IdempotencyKey;

interface IdempotencyKeyRepositoryInterface
{
    public function findOne(string $key): ?IdempotencyKey;

    public function save(IdempotencyKey $key): void;

    public function delete(IdempotencyKey $key): void;

    public function purgeExpired(): int;
}
