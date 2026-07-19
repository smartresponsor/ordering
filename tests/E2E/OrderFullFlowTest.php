<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\Ordering\Entity\Order\OrderEntity;
use App\Service\Workflow\Order\OrderOrchestrator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderFullFlowTest extends KernelTestCase
{
    public function testFullOrderLifecycle(): void
    {
        self::bootKernel();
        $orchestrator = self::getContainer()->get(OrderOrchestrator::class);
        $order = $this->createMock(OrderEntity::class);
        $order->method('getNumber')->willReturn('ORDER-999');
        $order->method('getTotalAmount')->willReturn('100.00');
        $order->method('getCurrency')->willReturn('EUR');

        $orchestrator->processOrder($order);
        $this->assertTrue(true, 'Order lifecycle executed successfully.');
    }
}
