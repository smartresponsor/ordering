<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\RepositoryInterface\Order;

interface OrderEventRepositoryInterface
{
    public function save(OrderEventRecord $record): void;

    public function findByOrder(string $orderId, int $limit = 100, int $offset = 0): iterable;

    public function findLastByOrder(string $orderId): ?OrderEventRecord;

    public function existsByEventId(string $eventId): bool;
}
