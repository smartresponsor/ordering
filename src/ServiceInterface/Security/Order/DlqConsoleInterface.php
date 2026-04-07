<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Security\Order;

interface DlqConsoleInterface
{
    public function seed(): void;

    /** @param array{provider?: string, reason?: string} $filter */
    public function showList(array $filter = []): void;

    public function requeue(string $id): void;

    public function discard(string $id, string $reason): void;
}
