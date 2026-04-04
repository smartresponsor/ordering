<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Webhook\Order;

interface WebhookSignerRsaInterface
{
    public function sign(string $payload, string $privatePem, string $kid): string;
}
