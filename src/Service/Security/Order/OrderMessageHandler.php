<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

use App\Entity\Order;
use App\Event\Domain\Order\OrderCancelledEvent;
use App\Event\Domain\Order\OrderPaidEvent;
use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\Event\Domain\Order\OrderShippedEvent;
use App\Message\OrderMessage;
use App\ServiceInterface\Security\Order\OrderMessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class OrderMessageHandler implements OrderMessageHandlerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(OrderMessage $m): void
    {
        $order = $this->em->find(Order::class, $m->orderId);
        if (!$order) {
            return;
        }
        $legacyId = $this->legacyNumericOrderId($order);
        $map = [
            OrderPlacedEvent::class => fn () => new OrderPlacedEvent($legacyId),
            OrderPaidEvent::class => fn () => new OrderPaidEvent($order->id(), $order->grandTotal(), $order->currency(), $order->id()),
            OrderShippedEvent::class => fn () => new OrderShippedEvent((string) $legacyId),
            OrderCancelledEvent::class => fn () => new OrderCancelledEvent($order),
            OrderRefundedEvent::class => fn () => new OrderRefundedEvent($order, $order->refundedTotal()),
        ];
        if (isset($map[$m->eventName])) {
            $this->dispatcher->dispatch($map[$m->eventName](), $m->eventName);
        }
    }

    private function legacyNumericOrderId(Order $order): int
    {
        $digits = preg_replace('/\D+/', '', $order->id());

        return is_string($digits) && '' != $digits ? (int) $digits : 0;
    }
}
