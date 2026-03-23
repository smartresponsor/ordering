<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Contract\Order\OrderShipmentGatewayInterface;
use App\Entity\Order\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderShipmentIntegrationTest extends KernelTestCase
{
    public function testShipmentFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderShipmentGatewayInterface::class);
        $this->assertNotNull($gateway);

        // NOTE: your Order constructor may differ; adapt in project
        $order = $this->createMock(Order::class);
        $order->method('getNumber')->willReturn('ORDER-002');
        $order->method('getTotalAmount')->willReturn('100.00');
        $order->method('getCurrency')->willReturn('USD');

        $tracking = $gateway->createShipment($order, 'DHL');
        $this->assertMatchesRegularExpression('/^DHL-/', $tracking);
    }
}
