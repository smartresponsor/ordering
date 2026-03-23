<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface JwkKeyInterface
{
    public function kid(): string;

    public function alg(): string;

    public function type(): string;

    public function publicPem(): string;

    public function privatePem(): ?string;

    public function active(): bool;
}
