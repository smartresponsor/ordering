<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class CspMiddleware
{
    private string $policy;

    public function __construct(string $policy = "default-src 'self'; frame-ancestors 'none'; object-src 'none';")
    {
        $this->policy = $policy;
    }

    public function apply(): void
    {
        header('Content-Security-Policy: '.$this->policy);
    }
}
