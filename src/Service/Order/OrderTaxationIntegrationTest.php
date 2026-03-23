<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Contract\Order\OrderTaxationGatewayInterface;
use App\Entity\Order\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderTaxationIntegrationTest extends KernelTestCase
{
    public function testTaxationFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderTaxationGatewayInterface::class);
        $this->assertNotNull($gateway);

        $order = $this->createMock(Order::class);
        $order->method('getNumber')->willReturn('ORDER-003');
        $order->method('getTotalAmount')->willReturn('100.00');
        $order->method('getCurrency')->willReturn('EUR');

        $breakdown = $gateway->calculate($order, 'DE');
        $this->assertSame('100.00', $breakdown->subtotal);
        $this->assertNotEmpty($breakdown->taxAmount);
        $this->assertNotEmpty($breakdown->total);
    }
}
