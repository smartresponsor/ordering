<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderDisputeInterface
{
    public function __construct(Order $order, string $type, ?string $reason, ?string $externalId = null);

    public function getId(): ?int;

    public function getOrder(): Order;

    public function getStatus(): string;

    public function getExternalId(): ?string;

    public function setStatus(string $status): void;

    public function markResolved(): void;
}
