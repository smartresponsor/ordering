<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPaymentIntentInterface
{
    public function __construct(Order $order, string $intentId, string $amount);

    public function getId(): ?int;

    public function getOrder(): Order;

    public function getIntentId(): string;

    public function getAmount(): string;

    public function getStatus(): PaymentStatus;

    public function markConfirmed(): void;

    public function markFailed(): void;
}
