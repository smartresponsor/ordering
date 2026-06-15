<?php

declare(strict_types=1);

namespace App\Service\State\Order;

use App\Entity\Order\OrderEntity;
use App\Event\Domain\Order\OrderStatusChanged;
use App\ServiceInterface\State\Order\OrderStateMachineInterface;
use App\ServiceInterface\Webhook\Order\WebhookDispatcherInterface;

final readonly class OrderStateMachine implements OrderStateMachineInterface
{
    public function __construct(private WebhookDispatcherInterface $dispatcher)
    {
    }

    public function place(OrderEntity $order): void
    {
        $this->transit($order, 'placed');
    }

    public function confirm(OrderEntity $order): void
    {
        $this->transit($order, 'paid');
    }

    public function fulfill(OrderEntity $order): void
    {
        $this->transit($order, 'shipped');
    }

    public function close(OrderEntity $order): void
    {
        $this->transit($order, 'completed');
    }

    public function cancel(OrderEntity $order): void
    {
        $this->transit($order, 'cancelled');
    }

    public function return(OrderEntity $order): void
    {
        $this->transit($order, 'refunded');
    }

    private function transit(OrderEntity $order, string $targetStatus): void
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
