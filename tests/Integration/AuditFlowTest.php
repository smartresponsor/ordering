<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order;
use App\Service\Analytics\Order\AuditLoggerService;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AuditFlowTest extends KernelTestCase
{
    public function testAuditLogCreated(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $audit = self::$kernel->getContainer()->get(AuditLoggerService::class);

        $order = new Order('V-1', new Money('10.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $audit->logEvent($order, 'order_tested', ['k' => 'v'], 'tester');
        $stmt = $em->getConnection()->executeQuery('SELECT count(*) FROM order_audit_log');
        $this->assertGreaterThan(0, (int) $stmt->fetchOne());
    }
}
