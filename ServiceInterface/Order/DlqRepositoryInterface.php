<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

interface DlqRepositoryInterface
{
    /** @return array<int,array<string,mixed>> */
    public function list(array $filter = []): array;

    public function get(string $dlqId): ?array;

    public function save(array $item): void;

    public function delete(string $dlqId): void;
}
