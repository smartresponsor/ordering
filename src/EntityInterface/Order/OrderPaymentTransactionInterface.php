<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPaymentTransactionInterface
{
    public function __construct(string $orderId, string $amount, string $method = 'unknown');

    public function id(): int;

    public function orderId(): string;

    public function amount(): string;

    public function status(): string;

    public function method(): string;

    public function transactionId(): ?string;

    public function succeed(string $txId): void;

    public function fail(): void;
}
