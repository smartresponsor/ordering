<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\WebhookSignerHmacInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class WebhookSignerHmac implements WebhookSignerHmacInterface
{
    public function sign(string $payload, string $secret): string
    {
        return base64_encode(hash_hmac('sha256', $payload, $secret, true));
    }
}
