<?php

declare(strict_types=1);

namespace Tests\Service\Order;

use PHPUnit\Framework\TestCase;

final class ContractConsistencyTest extends TestCase
{
    public function testInterfacesExist(): void
    {
        $this->assertTrue(interface_exists(\App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface::class));
        $this->assertTrue(interface_exists(\App\Ordering\Contract\Gateway\Order\OrderShipmentGatewayInterface::class));
        $this->assertTrue(interface_exists(\App\Ordering\Contract\Gateway\Order\OrderTaxationGatewayInterface::class));
    }
}
