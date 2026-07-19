<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\Ordering\Entity\Order\OrderEntity;
use PHPUnit\Framework\TestCase;

final class OrderReplayEndToEndTest extends TestCase
{
    public function testPartialPaymentTransitionsAndEvents(): void
    {
        $order = new OrderEntity('USD', '100.00');
        $order->applyPartialPayment('50.00', 'ref-1', true);
        $this->assertSame('partially_paid', $order->status());

        $events = $order->releaseEvents();
        $this->assertNotEmpty($events, 'Domain events should be released');
    }
}
