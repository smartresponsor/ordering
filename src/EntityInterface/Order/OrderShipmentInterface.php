<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderShipmentInterface
{
    public function __construct(Order $order, ?string $carrier = null, ?string $tracking = null);

    public function getId(): ?int;

    public function getOrder(): Order;

    public function getStatus(): string;

    public function getDeliveredAt(): ?\DateTimeImmutable;

    public function markInTransit(): void;

    public function markDelivered(\DateTimeInterface $at): void;

    public function markCompleted(): void;

    public function markFailed(): void;
}
