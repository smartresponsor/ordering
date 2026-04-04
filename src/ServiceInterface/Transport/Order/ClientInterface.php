<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Transport\Order;

interface ClientInterface
{
    public function createOrder(int $totalAmount, string $currency, string $customerId, array $meta = []): array;

    public function getOrder(string $id): array;

    public function transition(string $id, string $action, ?string $idemKey = null): void;

    public function authorizePayment(string $orderId, int $amount, string $currency): array;

    public function capturePayment(string $orderId, string $providerPaymentId, int $amount): array;

    public function refundPayment(string $orderId, string $providerPaymentId, int $amount): array;
}
