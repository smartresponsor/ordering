<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderMetricsProjectionEntity;
use App\Service\Analytics\Order\MetricsProjectionService;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MetricsProjectionFlowTest extends KernelTestCase
{
    public function testProjectionUpdates(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(MetricsProjectionService::class);

        $order = new OrderEntity('V-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $svc->projectOrderPlaced($order->id(), '100.00', 'V-1', new \DateTimeImmutable('2025-01-02'));
        $svc->projectRefund('10.00', new \DateTimeImmutable('2025-01-02'));
        $em->flush();

        $repo = $em->getRepository(OrderMetricsProjectionEntity::class);
        $day = $repo->findOneBy(['date' => new \DateTimeImmutable('2025-01-02')]);
        $this->assertNotNull($day);
        $this->assertEquals(1, $day->getOrdersCount());
        $this->assertEquals('90.00', $day->getNetTotal());
    }
}
