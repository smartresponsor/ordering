<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface PaymentWebhookLogInterface
{
    public function __construct(string $provider, string $eventId, string $payloadHash);

    public function getId(): ?int;
}
