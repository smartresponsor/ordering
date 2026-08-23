<?php

declare(strict_types=1);

namespace App\Ordering\MessageHandler;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderCancelledEvent;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Event\Domain\Order\OrderPlacedEvent;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;
use App\Ordering\Event\Domain\Order\OrderShippedEvent;
use App\Ordering\Message\OrderMessage;
use App\Ordering\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class OrderMessageHandler
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(OrderMessage $m): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($m->orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }
        $map = [
            OrderPlacedEvent::class => fn () => new OrderPlacedEvent($order->slug()),
            OrderPaidEvent::class => fn () => new OrderPaidEvent($order->slug(), $order->grandTotal(), $order->currency(), $order->slug()),
            OrderShippedEvent::class => fn () => new OrderShippedEvent(
                $order->slug(),
                null,
                $order->getTrackingCode(),
            ),
            OrderCancelledEvent::class => fn () => new OrderCancelledEvent(
                $order->slug(),
                $order->getVendorId(),
            ),
            OrderRefundedEvent::class => fn () => new OrderRefundedEvent(
                $order->slug(),
                $order->refundedTotal(),
                $order->currency(),
                $order->getVendorId(),
            ),
        ];
        if (isset($map[$m->eventName])) {
            $this->dispatcher->dispatch($map[$m->eventName](), $m->eventName);
        }
    }
}
