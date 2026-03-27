<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\Order;
use App\Service\Order\MetricsProjectionService;
use App\Service\Order\OrderMetricsProjection;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MetricsProjectionFlowTest extends KernelTestCase
{
    public function testProjectionUpdates(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(MetricsProjectionService::class);

        $order = new Order('V-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $svc->projectOrderPlaced($order, '100.00', 'V-1', new \DateTimeImmutable('2025-01-02'));
        $svc->projectRefund('10.00', new \DateTimeImmutable('2025-01-02'));
        $em->flush();

        $repo = $em->getRepository(OrderMetricsProjection::class);
        $day = $repo->findOneBy(['date' => new \DateTimeImmutable('2025-01-02')]);
        $this->assertNotNull($day);
        $this->assertEquals(1, $day->getOrdersCount());
        $this->assertEquals('90.00', $day->getNetTotal());
    }
}
