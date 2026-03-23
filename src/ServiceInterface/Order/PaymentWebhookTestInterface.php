<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface PaymentWebhookTestInterface
{
    public function test_payment_webhook_happy_path(): void;

    public function test_payment_webhook_duplicate_is_ignored(): void;
}
