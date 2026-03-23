<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Contract\Order\OrderPaymentGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderPaymentIntegrationTest extends KernelTestCase
{
    public function testPaymentFlowUsesGatewayContract(): void
    {
        self::bootKernel();
        $gateway = self::getContainer()->get(OrderPaymentGatewayInterface::class);
        $payment = $gateway->initiatePayment('ORDER-001', 199.99, 'USD');
        $this->assertNotNull($payment);
        $this->assertTrue(method_exists($gateway, 'refundPayment'));
    }
}
