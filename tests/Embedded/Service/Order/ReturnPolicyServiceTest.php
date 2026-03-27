<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace Tests\Embedded\Service\Order;

use App\Service\Order\OrderReturnPolicyService;
use PHPUnit\Framework\TestCase;

final class ReturnPolicyServiceTest extends TestCase
{
    public function testAllowsWithinWindow(): void
    {
        $svc = new OrderReturnPolicyService(14);
        $deliveredAt = new \DateTimeImmutable('-7 days');
        $this->assertTrue($svc->canReturn($deliveredAt));
    }

    public function testRejectsAfterWindow(): void
    {
        $svc = new OrderReturnPolicyService(14);
        $deliveredAt = new \DateTimeImmutable('-20 days');
        $this->assertFalse($svc->canReturn($deliveredAt));
    }
}
