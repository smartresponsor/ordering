<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Refund\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ServiceInterface\Refund\Order\OrderRefundEligibilityServiceInterface;
use App\Ordering\ServiceInterface\Refund\Order\OrderServiceHookInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;

final readonly class OrderServiceHook implements OrderServiceHookInterface
{
    public function __construct(private OrderRefundEligibilityServiceInterface $eligibility)
    {
    }

    public function assertRefundAllowed(OrderEntity $order, Money $amount): void
    {
        if (!$this->eligibility->canRefund($order)) {
            throw new \DomainException('Refund not allowed: delivery window expired or not delivered policy.');
        }
    }
}
