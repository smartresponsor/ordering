<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Workflow\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\Event\Domain\Order\OrderCancelledEvent;
use App\Event\Domain\Order\OrderPaidEvent;
use App\Event\Domain\Order\OrderPlacedEvent;
use App\Event\Domain\Order\OrderRefundedEvent;
use App\Event\Domain\Order\OrderShippedEvent;
use App\Service\Outbox\OutboxPublisher;
use App\Service\Payment\PaymentProcessorService;
use App\Service\Shipment\ShipmentProcessorService;
use App\ServiceInterface\Inventory\InventoryServiceInterface;
use App\ServiceInterface\Pricing\Order\PriceCalculatorInterface;
use App\ServiceInterface\Workflow\Order\OrderWorkflowServiceInterface;
use App\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class OrderWorkflowService implements OrderWorkflowServiceInterface
{
    public function __construct(
        private WorkflowInterface $workflow,
        private EntityManagerInterface $em,
        private ShipmentProcessorService $shipper,
        private PaymentProcessorService $payments,
        private PriceCalculatorInterface $calculator,
        private InventoryServiceInterface $inventory,
        private OutboxPublisher $outbox,
    ) {
    }

    /**
     * @param OrderItem[] $items
     *
     * @throws ExceptionInterface
     */
    public function place(Order $order, array $items): void
    {
        $this->apply($order, 'place');
        $this->calculator->recalc($order, $items);
        $this->inventory->reserve($items);
        $this->outbox->publish(OrderPlacedEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    public function pay(Order $order, int $amount): void
    {
        $this->payments->charge($order, $amount);
        $this->apply($order, 'pay');
        $this->outbox->publish(OrderPaidEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    public function ship(Order $order): void
    {
        $this->shipper->ship($order);
        $this->apply($order, 'ship');
        $this->outbox->publish(OrderShippedEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    private function apply(Order $order, string $transition): void
    {
        if (!$this->workflow->can($order, $transition)) {
            throw new \LogicException("Transition '$transition' not allowed");
        }
        $this->workflow->apply($order, $transition);
        $order->setStatus(match ($transition) {
            'place' => OrderStatus::Placed,
            'pay' => OrderStatus::Paid,
            'ship' => OrderStatus::Shipped,
            default => $order->getStatus(),
        });
        $this->em->persist($order);
    }

    public function cancel(Order $order): void
    {
        $this->apply($order, 'cancel');
        $this->publish(OrderCancelledEvent::class, $order);
    }

    public function refund(Order $order): void
    {
        $this->apply($order, 'refund');
        $this->publish(OrderRefundedEvent::class, $order);
    }

    private function publish(string $eventClass, Order $order): void
    {
        $this->outbox->publish($eventClass, ['orderId' => $order->getId()]);
        $this->em->flush();
    }
}
