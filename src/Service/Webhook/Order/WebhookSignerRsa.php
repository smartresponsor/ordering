<?php

declare(strict_types=1);

namespace App\Service\Webhook\Order;

use App\ServiceInterface\Webhook\Order\WebhookSignerRsaInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class WebhookSignerRsa implements WebhookSignerRsaInterface
{
    public function sign(string $payload, string $privatePem, string $kid): string
    {
        $sig = '';
        openssl_sign($payload, $sig, $privatePem, OPENSSL_ALGO_SHA256);

        return (string) json_encode([
            'kid' => $kid,
            'alg' => 'RSA-SHA256',
            'sig' => base64_encode($sig),
        ]);
    }
}
