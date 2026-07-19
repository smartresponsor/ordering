<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\Ordering\Entity\Order\OrderEntity;
use PHPUnit\Framework\TestCase;

final class PartialShipmentTest extends TestCase
{
    public function testShipAfterPaid(): void
    {
        $o = new OrderEntity('USD', '100.00');
        $o->applyPartialPayment('100.00', 'paid', false);
        $o->shipItems(1, 'box-1');
        $this->assertSame('partially_shipped', $o->status());
    }
}
