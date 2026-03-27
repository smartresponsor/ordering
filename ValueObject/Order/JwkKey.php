<?php

declare(strict_types=1);

namespace App\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class JwkKey
{
    private string $kid;
    private string $alg;
    private string $type;
    private string $publicPem;
    private ?string $privatePem;
    private bool $active;

    public function __construct(string $kid, string $alg, string $type, string $publicPem, ?string $privatePem, bool $active)
    {
        $this->kid = $kid;
        $this->alg = $alg;
        $this->type = $type;
        $this->publicPem = $publicPem;
        $this->privatePem = $privatePem;
        $this->active = $active;
    }

    public function kid(): string
    {
        return $this->kid;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function publicPem(): string
    {
        return $this->publicPem;
    }

    public function privatePem(): ?string
    {
        return $this->privatePem;
    }

    public function active(): bool
    {
        return $this->active;
    }
}
