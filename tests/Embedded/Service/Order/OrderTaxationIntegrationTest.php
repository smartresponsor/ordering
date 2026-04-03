<?php

declare(strict_types=1);

namespace Tests\Service\Order;

use App\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderTaxationIntegrationTest extends KernelTestCase
{
    public function testTaxationFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderTaxationGatewayInterface::class);
        $this->assertNotNull($gateway);

        $breakdown = $gateway->calculate(
            'ORDER-003',
            [['sku' => 'SKU-1', 'qty' => 1, 'price' => '100.00']],
            ['country' => 'DE', 'currency' => 'EUR']
        );
        $this->assertArrayHasKey('subtotal', $breakdown);
        $this->assertArrayHasKey('taxAmount', $breakdown);
        $this->assertArrayHasKey('total', $breakdown);
    }
}
