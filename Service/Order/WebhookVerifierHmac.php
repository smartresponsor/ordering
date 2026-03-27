<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\WebhookVerifierHmacInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class WebhookVerifierHmac implements WebhookVerifierHmacInterface
{
    public function verify(string $payload, string $secret, string $signature): bool
    {
        $calc = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        return hash_equals($calc, trim($signature));
    }
}
