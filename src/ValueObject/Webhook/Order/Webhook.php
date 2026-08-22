<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Webhook\Order;

final class Webhook
{
    public static function verifyStripe(string $payload, string $sigHeader, string $secret): bool
    {
        $parts = [];
        foreach (explode(',', $sigHeader) as $kv) {
            $kv = trim($kv);
            if (str_contains($kv, '=')) {
                [$k, $v] = explode('=', $kv, 2);
                $parts[$k] = $v;
            }
        }
        if (!isset($parts['t'], $parts['v1'])) {
            return false;
        }
        $signed = $parts['t'].'.'.$payload;
        $computed = hash_hmac('sha256', $signed, $secret);

        return hash_equals($computed, $parts['v1']);
    }
}
