<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Security\Order;

interface ChaosProvInterface
{
    public function authorize(string $orderId, int $amount, string $currency, array $meta = []): array;

    public function capture(string $paymentId, int $amount): array;

    public function refund(string $paymentId, int $amount): array;

    public function verifyWebhook(string $payload, string $signatureHeader): bool;

    public function mapEvent(array $event): array;
}
