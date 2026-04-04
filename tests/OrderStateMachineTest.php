<?php

declare(strict_types=1);

namespace Tests;

use App\Entity\Order\Entity\Order\Order;
use App\Service\State\Order\OrderStateMachine;
use App\Webhook\NoopWebhookDispatcher;
use PHPUnit\Framework\TestCase;

final class OrderStateMachineTest extends TestCase
{
    public function testHappyFlow(): void
    {
        $stateMachine = new OrderStateMachine(new NoopWebhookDispatcher());
        $order = new Order('ord_test', 1000, 'USD', 'cus_1');

        $stateMachine->place($order);
        $stateMachine->confirm($order);
        $stateMachine->fulfill($order);
        $stateMachine->close($order);

        self::assertSame('completed', $order->status());
    }

    public function testInvalidTransitionThrows(): void
    {
        $stateMachine = new OrderStateMachine(new NoopWebhookDispatcher());
        $order = new Order('ord_test', 1000, 'USD', 'cus_1');

        $this->expectException(\InvalidArgumentException::class);
        $stateMachine->confirm($order);
    }
}
