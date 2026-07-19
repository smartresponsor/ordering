<?php

declare(strict_types=1);

namespace Tests;

use App\Ordering\Entity\Order\OrderEntity;
use App\Service\State\Order\OrderStateMachine;
use App\Webhook\NoopWebhookDispatcher;
use PHPUnit\Framework\TestCase;

final class OrderStateMachineTest extends TestCase
{
    public function testHappyFlow(): void
    {
        $stateMachine = new OrderStateMachine(new NoopWebhookDispatcher());
        $order = OrderEntity::create('USD', '10.00');

        $stateMachine->place($order);
        $stateMachine->confirm($order);
        $stateMachine->fulfill($order);
        $stateMachine->close($order);

        self::assertSame('completed', $order->status());
    }

    public function testInvalidTransitionThrows(): void
    {
        $stateMachine = new OrderStateMachine(new NoopWebhookDispatcher());
        $order = OrderEntity::create('USD', '10.00');

        $this->expectException(\InvalidArgumentException::class);
        $stateMachine->confirm($order);
    }
}
