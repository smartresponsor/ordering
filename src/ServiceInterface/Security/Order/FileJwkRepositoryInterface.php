<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Security\Order;

use App\ValueObject\Security\Order\JwkKey;

interface FileJwkRepositoryInterface
{
    public function listActive(): array;

    public function findByKid(string $kid): ?JwkKey;

    public function save(JwkKey $key): void;

    public function deactivate(string $kid): void;
}
