<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\OrderReturnPolicyServiceInterface;
use App\ServiceInterface\Order\ReturnPolicyServiceInterface;

final class ReturnPolicyService implements ReturnPolicyServiceInterface, OrderReturnPolicyServiceInterface
{
    public function __construct(
        private int $refundWindowDays = 14,
    ) {
    }

    public function canReturn(?\DateTimeImmutable $deliveredAt, ?\DateTimeImmutable $now = null): bool
    {
        if (null === $deliveredAt) {
            return false;
        }

        $now ??= new \DateTimeImmutable();
        $diff = $now->getTimestamp() - $deliveredAt->getTimestamp();

        return $diff <= ($this->refundWindowDays * 86400);
    }
}
