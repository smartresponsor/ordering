<?php

declare(strict_types=1);

namespace App\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

interface JwkRepositoryInterface
{
    /** @return array<int,JwkKey> */
    public function listActive(): array;

    public function findByKid(string $kid): ?JwkKey;

    public function save(JwkKey $key): void;

    public function deactivate(string $kid): void;
}
