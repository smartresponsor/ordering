<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\ServiceInterface\Security\Order\JwkRepositoryInterface;
use App\Ordering\ServiceInterface\Security\Order\KeyRotationManagerInterface;
use App\Ordering\ValueObject\Security\Order\JwkKey;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final readonly class KeyRotationManager implements KeyRotationManagerInterface
{
    public function __construct(
        private JwkRepositoryInterface $repo,
        private SecretRotationPolicy $policy,
    ) {
    }

    public function rotate(string $oldKid, string $newKid, string $publicPem, string $privatePem): void
    {
        // Deactivate old, add new active
        $this->repo->deactivate($oldKid);
        $new = new JwkKey($newKid, 'RS256', 'RSA', $publicPem, $privatePem, true);
        $this->repo->save($new);
    }
}
