<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Service\Analytics\Order\AuditLoggerService;
use App\ServiceInterface\Archival\Order\OrderAuditTrailBuilderInterface;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AuditFlowTest extends KernelTestCase
{
    public function testAuditLogCreated(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        /** @var AuditLoggerService $audit */
        $audit = self::getContainer()->get(AuditLoggerService::class);
        /** @var OrderAuditTrailBuilderInterface $builder */
        $builder = self::getContainer()->get(OrderAuditTrailBuilderInterface::class);

        $order = new OrderEntity('V-1', new Money('10.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $audit->logEvent($order, 'order_tested', ['k' => 'v'], 'tester');
        $trail = $builder->buildForOrder('V-1');
        Assert::assertGreaterThan(0, $trail->totalEvents);
    }
}
