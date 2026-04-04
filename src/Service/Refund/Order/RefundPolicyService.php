<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Refund\Order;

final class RefundPolicyService
{
    public function __construct(
        private int $postDeliveryRefundDays = 14,
        private bool $allowPartialAfterShipment = true,
    ) {
    }

    public function canRefund(\DateTimeImmutable $deliveredAt, \DateTimeImmutable $now = new \DateTimeImmutable('now')): bool
    {
        return $now <= $deliveredAt->modify('+'.$this->postDeliveryRefundDays.' days');
    }

    public function allowPartialAfterShipment(): bool
    {
        return $this->allowPartialAfterShipment;
    }
}
