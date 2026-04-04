<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

interface CryptoServiceInterface
{
    public function __construct(string $key);

    public function encrypt(string $plaintext): string;

    public function decrypt(string $encoded): string;
}
