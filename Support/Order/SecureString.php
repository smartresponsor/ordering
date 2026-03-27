<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Support\Order;

final class SecureString
{
    public static function mask(?string $value, int $visible = 4): ?string
    {
        if (null === $value || '' === $value) {
            return $value;
        }
        $len = strlen($value);
        if ($len <= $visible) {
            return str_repeat('*', $len);
        }

        return str_repeat('*', max(0, $len - $visible)).substr($value, -$visible);
    }
}
