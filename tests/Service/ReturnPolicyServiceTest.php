<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\Order\ReturnPolicyService;
use PHPUnit\Framework\TestCase;

final class ReturnPolicyServiceTest extends TestCase
{
    public function testAllowsWithinWindow(): void
    {
        $svc = new ReturnPolicyService(14);
        $deliveredAt = new \DateTimeImmutable('-7 days');
        $this->assertTrue($svc->canReturn($deliveredAt));
    }

    public function testRejectsAfterWindow(): void
    {
        $svc = new ReturnPolicyService(14);
        $deliveredAt = new \DateTimeImmutable('-20 days');
        $this->assertFalse($svc->canReturn($deliveredAt));
    }
}
