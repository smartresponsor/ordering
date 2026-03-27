<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

interface IdempotencyStoreInterface
{
    public function setIfAbsent(string $keyHash, int $ttlSeconds): bool;

    public function has(string $keyHash): bool;
}
