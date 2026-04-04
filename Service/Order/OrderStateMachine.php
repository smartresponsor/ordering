<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\Entity\Order\Entity\Order\Order;
use App\Event\Domain\Order\OrderStatusChanged;
use App\ServiceInterface\Order\OrderStateMachineInterface;
use App\ServiceInterface\Order\WebhookDispatcherInterface;

final class OrderStateMachine implements OrderStateMachineInterface
{
    public function __construct(private WebhookDispatcherInterface $dispatcher)
    {
    }

    public function place(Order $order): void
    {
        $this->transit($order, 'placed');
    }

    public function confirm(Order $order): void
    {
        $this->transit($order, 'paid');
    }

    public function fulfill(Order $order): void
    {
        $this->transit($order, 'shipped');
    }

    public function close(Order $order): void
    {
        $this->transit($order, 'completed');
    }

    public function cancel(Order $order): void
    {
        $this->transit($order, 'cancelled');
    }

    public function return(Order $order): void
    {
        $this->transit($order, 'refunded');
    }

    private function transit(Order $order, string $targetStatus): void
    {
        $from = $order->status();
        $allowed = match ($from) {
            'draft' => ['placed'],
            'placed' => ['paid', 'cancelled'],
            'paid' => ['shipped', 'refunded', 'cancelled'],
            'shipped' => ['completed', 'refunded'],
            'completed', 'cancelled', 'refunded' => [],
            default => throw new \InvalidArgumentException(sprintf('Unknown order status "%s".', $from)),
        };

        if ($from === $targetStatus) {
            return;
        }

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \InvalidArgumentException(sprintf('Transition from %s to %s is not allowed.', $from, $targetStatus));
        }

        $order->setStatus($targetStatus);
        $this->dispatcher->dispatch(new OrderStatusChanged($order->id(), $from, $targetStatus));
    }
}
