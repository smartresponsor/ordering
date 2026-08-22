<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Workflow\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Event\Domain\Order\OrderCancelledEvent;
use App\Ordering\Event\Domain\Order\OrderPaidEvent;
use App\Ordering\Event\Domain\Order\OrderRefundedEvent;
use App\Ordering\Event\Domain\Order\OrderShippedEvent;
use App\Ordering\Service\Outbox\OutboxPublisher;
use App\Ordering\Service\Payment\PaymentProcessorService;
use App\Ordering\Service\Shipment\ShipmentProcessorService;
use App\Ordering\ServiceInterface\Inventory\InventoryServiceInterface;
use App\Ordering\ServiceInterface\Pricing\Order\PriceCalculatorInterface;
use App\Ordering\ServiceInterface\Workflow\Order\OrderWorkflowServiceInterface;
use App\Ordering\ValueObject\OrderStatus;
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
    public function place(OrderEntity $order, array $items): void
    {
        if (!$this->workflow->can($order, 'place')) {
            throw new \LogicException("Transition 'place' not allowed");
        }

        $this->workflow->apply($order, 'place');
        $order->place();
        $this->em->persist($order);
        $this->calculator->recalc($order, $items);
        $this->inventory->reserve($items);
        $this->em->flush();
    }

    public function pay(OrderEntity $order, int $amount): void
    {
        $this->payments->charge($order, $amount);
        $this->apply($order, 'pay');
        $this->outbox->publish(OrderPaidEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    public function ship(OrderEntity $order): void
    {
        $this->shipper->ship($order);
        $this->apply($order, 'ship');
        $this->outbox->publish(OrderShippedEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    private function apply(OrderEntity $order, string $transition): void
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

    public function cancel(OrderEntity $order): void
    {
        $this->apply($order, 'cancel');
        $this->publish(OrderCancelledEvent::class, $order);
    }

    public function refund(OrderEntity $order): void
    {
        $this->apply($order, 'refund');
        $this->publish(OrderRefundedEvent::class, $order);
    }

    private function publish(string $eventClass, OrderEntity $order): void
    {
        $this->outbox->publish($eventClass, ['orderId' => $order->getId()]);
        $this->em->flush();
    }
}
