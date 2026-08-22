<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Contract\Gateway\Order\OrderShipmentGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderShipmentIntegrationTest extends KernelTestCase
{
    public function testShipmentFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderShipmentGatewayInterface::class);
        $this->assertNotNull($gateway);

        $tracking = $gateway->ship('ORDER-002', 'DHL', ['currency' => 'USD']);
        $this->assertNotSame('', $tracking);
    }
}
