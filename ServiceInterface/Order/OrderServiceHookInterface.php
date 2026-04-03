<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order;
use App\Service\Order\OrderRefundEligibilityService;
use App\ValueObject\Order\Money;

interface OrderServiceHookInterface
{
    public function __construct(OrderRefundEligibilityService $eligibility);

    public function assertRefundAllowed(Order $order, Money $amount): void;
}
