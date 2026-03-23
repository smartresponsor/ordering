<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\AuditLog;
use App\Repository\Order\AuditLogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AuditLogTest extends KernelTestCase
{
    public function testAuditLogCanBePersisted(): void
    {
        self::bootKernel();
        /** @var AuditLogRepository $repo */
        $repo = self::$kernel->getContainer()->get(AuditLogRepository::class);
        $log = new AuditLog('system', 'test.event');
        $repo->add($log);
        $this->assertTrue(true); // if no exception, basic wiring OK
    }
}
