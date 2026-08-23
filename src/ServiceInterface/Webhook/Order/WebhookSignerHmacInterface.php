<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Webhook\Order;

interface WebhookSignerHmacInterface
{
    public function sign(string $payload, string $secret): string;
}
