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
    /**
     * @param array{provider?: string, reason?: string} $filter
     *
     * @return list<array<string, mixed>>
     */
    public function list(array $filter = []): array;

    /** @return array<string, mixed>|null */
    public function get(string $dlqId): ?array;

    /** @param array<string, mixed> $item */
    public function save(array $item): void;

    public function delete(string $dlqId): void;
}
