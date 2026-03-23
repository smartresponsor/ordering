<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderPaymentInterface
{
    public function __construct(Order $order, string $paymentId, string $amount, string $currency);

    public function getOrder(): Order;

    public function getAmount(): string;

    public function getCurrency(): string;

    public function getStatus(): string;

    public function setStatus(string $status): void;

    public function getRefundedAmount(): string;

    public function addRefundedAmount(string $amount): void;
}
