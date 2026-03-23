<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\WebhookVerifierRsaInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class WebhookVerifierRsa implements WebhookVerifierRsaInterface
{
    public function verify(string $payload, string $publicPem, string $envelope): bool
    {
        $data = json_decode($envelope, true);
        if (!is_array($data)) {
            return false;
        }

        $sig = base64_decode((string) ($data['sig'] ?? ''), true);
        if (false === $sig) {
            return false;
        }

        return 1 === openssl_verify($payload, $sig, $publicPem, OPENSSL_ALGO_SHA256);
    }
}
