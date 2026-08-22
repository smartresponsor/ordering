<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderPaymentIntegrationTest extends KernelTestCase
{
    public function testPaymentFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderPaymentGatewayInterface::class);
        $chargeId = $gateway->charge('ORDER-001', '199.99', ['currency' => 'USD']);
        $this->assertNotSame('', $chargeId);

        $refundId = $gateway->refund('ORDER-001', '50.00', ['currency' => 'USD']);
        $this->assertNotSame('', $refundId);
    }
}
