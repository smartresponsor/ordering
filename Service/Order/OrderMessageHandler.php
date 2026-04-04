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
use App\ServiceInterface\Order\OrderMessageHandlerInterface;
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
        $map = [
            OrderPlacedEvent::class => fn () => new OrderPlacedEvent($order),
            OrderPaidEvent::class => fn () => new OrderPaidEvent($order),
            OrderShippedEvent::class => fn () => new OrderShippedEvent($order),
            OrderCancelledEvent::class => fn () => new OrderCancelledEvent($order),
            OrderRefundedEvent::class => fn () => new OrderRefundedEvent($order),
        ];
        if (isset($map[$m->eventName])) {
            $this->dispatcher->dispatch($map[$m->eventName](), $m->eventName);
        }
    }
}
