<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final readonly class SecretRotationPolicy
{
    private int $graceSecond;
    private int $lifeSecond;

    public function __construct(int $graceSecond = 86400, int $lifeSecond = 2592000)
    {
        $this->graceSecond = $graceSecond;
        $this->lifeSecond = $lifeSecond;
    }

    public function graceSecond(): int
    {
        return $this->graceSecond;
    }

    public function lifeSecond(): int
    {
        return $this->lifeSecond;
    }
}
