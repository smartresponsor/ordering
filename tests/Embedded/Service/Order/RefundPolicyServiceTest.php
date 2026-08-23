<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\Refund\Order\RefundPolicyService;
use PHPUnit\Framework\TestCase;

final class RefundPolicyServiceTest extends TestCase
{
    public function testRefundAllowedWithinWindow(): void
    {
        $svc = new RefundPolicyService(14, true);
        $deliveredAt = new \DateTimeImmutable('-7 days');
        $this->assertTrue($svc->canRefund($deliveredAt));
    }

    public function testRefundDeniedOutsideWindow(): void
    {
        $svc = new RefundPolicyService(14, true);
        $deliveredAt = new \DateTimeImmutable('-30 days');
        $this->assertFalse($svc->canRefund($deliveredAt));
    }
}
