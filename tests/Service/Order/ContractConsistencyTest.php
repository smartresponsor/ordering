<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use PHPUnit\Framework\TestCase;

final class ContractConsistencyTest extends TestCase
{
    public function testInterfacesExist(): void
    {
        $this->assertTrue(interface_exists(\App\Contract\Order\OrderPaymentGatewayInterface::class));
        $this->assertTrue(interface_exists(\App\Contract\Order\OrderShipmentGatewayInterface::class));
        $this->assertTrue(interface_exists(\App\Contract\Order\OrderTaxationGatewayInterface::class));
    }
}
