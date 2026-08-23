<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Service\Analytics\Order\AuditLoggerService;
use App\Ordering\ServiceInterface\Archival\Order\OrderAuditTrailBuilderInterface;
use App\Ordering\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AuditLogTest extends KernelTestCase
{
    public function testAuditLogCanBePersisted(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        /** @var AuditLoggerService $audit */
        $audit = self::getContainer()->get(AuditLoggerService::class);
        /** @var OrderAuditTrailBuilderInterface $builder */
        $builder = self::getContainer()->get(OrderAuditTrailBuilderInterface::class);

        $order = new OrderEntity('A-1', new Money('10.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $audit->logEvent($order, 'test.event', ['source' => 'audit-log-test'], 'system');
        $trail = $builder->buildForOrder('A-1');
        Assert::assertGreaterThan(0, $trail->totalEvents);
    }
}
