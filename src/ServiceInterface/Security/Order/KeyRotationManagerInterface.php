<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>.
 */

namespace App\ServiceInterface\Security\Order;

use App\ServiceInterface\Security\Order\JwkRepositoryInterface;

interface KeyRotationManagerInterface
{
    public function __construct(JwkRepositoryInterface $repo, SecretRotationPolicy $policy);

    public function rotate(string $oldKid, string $newKid, string $publicPem, string $privatePem): void;
}
