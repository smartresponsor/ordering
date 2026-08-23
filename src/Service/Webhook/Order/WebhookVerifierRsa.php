<?php

declare(strict_types=1);

namespace App\Ordering\Service\Webhook\Order;

use App\Ordering\ServiceInterface\Webhook\Order\WebhookVerifierRsaInterface;

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

        $signature = base64_decode((string) ($data['sig'] ?? ''), true);
        if (false === $signature) {
            return false;
        }

        return 1 === openssl_verify($payload, $signature, $publicPem, OPENSSL_ALGO_SHA256);
    }
}
