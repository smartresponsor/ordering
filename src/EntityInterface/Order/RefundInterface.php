<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface RefundInterface
{
    public function __construct(string $orderId, string $refundId, string $currency, string $amount, ?string $reason = null);

    public function getId(): int;

    public function getOrderId(): string;

    public function getRefundId(): string;

    public function getCurrency(): string;

    public function getAmount(): string;

    public function getReason(): ?string;

    public function getCreatedAt(): \DateTimeImmutable;
}
