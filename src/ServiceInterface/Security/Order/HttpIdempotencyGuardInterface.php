<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Security\Order;

interface HttpIdempotencyGuardInterface
{
    /** @param array<string, string|array<int, string>> $header */
    public function allow(string $method, string $path, string $body, array $header): bool;
}
