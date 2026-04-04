<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Support\Security\Order\SecureString;

final class MaskingService
{
    /** @var string[] */
    private array $maskKeys;

    public function __construct(array $maskKeys = [])
    {
        $this->maskKeys = array_map('strtolower', $maskKeys);
    }

    /** @param array<string,mixed> $data */
    public function maskArray(array $data): array
    {
        $masked = [];
        foreach ($data as $k => $v) {
            $lk = strtolower((string) $k);
            if (in_array($lk, $this->maskKeys, true)) {
                $masked[$k] = is_string($v) ? SecureString::mask($v, 2) : '***';
            } elseif (is_array($v)) {
                $masked[$k] = $this->maskArray($v);
            } else {
                $masked[$k] = $v;
            }
        }

        return $masked;
    }
}
