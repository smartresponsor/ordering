<?php

declare(strict_types=1);

namespace App\Ordering\MessageHandler;

use App\Ordering\Message\OrderEventMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class OrderEventMessageHandler
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    public function __invoke(OrderEventMessage $m): void
    {
        if (class_exists($m->eventName)) {
            $ev = new $m->eventName($m->orderId);
            $this->dispatcher->dispatch($ev, $m->eventName);
        }
    }
}
