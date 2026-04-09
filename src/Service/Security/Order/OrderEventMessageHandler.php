<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

use App\Message\OrderEventMessage;
use App\ServiceInterface\Security\Order\OrderEventMessageHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class OrderEventMessageHandler implements OrderEventMessageHandlerInterface
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher) {
    }

    public function __invoke(OrderEventMessage $m): void
    {
        if (class_exists($m->eventName)) {
            $ev = new $m->eventName($m->orderId);
            $this->dispatcher->dispatch($ev, $m->eventName);
        }
    }
}
