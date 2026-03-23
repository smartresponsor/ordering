<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPaymentPartInterface
{
    public function __construct(string $orderId, string $paymentId, string $currency, string $amount);

    public function getId(): int;

    public function getOrderId(): string;

    public function getPaymentId(): string;

    public function getCurrency(): string;

    public function getAmount(): string;

    public function getCreatedAt(): \DateTimeImmutable;
}
