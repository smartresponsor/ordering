<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>.
 */

namespace App\ServiceInterface\Security\Order;

interface KeyRotationManagerInterface
{
    public function rotate(string $oldKid, string $newKid, string $publicPem, string $privatePem): void;
}
