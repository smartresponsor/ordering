<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderTransactionInterface
{
    public function __construct(Order $order, string $externalId, string $amount, string $currency);

    public function getId(): ?int;

    public function getOrder(): Order;

    public function getExternalId(): string;

    public function getAmount(): string;

    public function getCurrency(): string;

    public function getStatus(): PaymentStatus;

    public function confirm(): void;

    public function fail(): void;
}
