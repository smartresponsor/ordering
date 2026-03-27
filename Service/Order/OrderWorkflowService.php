<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\Service\Inventory\InventoryServiceInterface;
use App\ServiceInterface\Order\PriceCalculatorInterface;
use App\Service\Outbox\OutboxPublisher;
use App\Service\Payment\PaymentProcessorService;
use App\Service\Shipment\ShipmentProcessorService;
use App\ServiceInterface\Order\OrderWorkflowServiceInterface;
use App\ValueObject\Order\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final class OrderWorkflowService implements OrderWorkflowServiceInterface
{
    public function __construct(
        private readonly WorkflowInterface $workflow,
        private readonly EntityManagerInterface $em,
        private readonly ShipmentProcessorService $shipper,
        private readonly PaymentProcessorService $payments,
        private readonly PriceCalculatorInterface $calculator,
        private readonly InventoryServiceInterface $inventory,
        private readonly OutboxPublisher $outbox,
    ) {
    }

    /** @param OrderItem[] $items */
    public function place(Order $order, array $items): void
    {
        $this->apply($order, 'place');
        $this->calculator->recalc($order, $items);
        $this->inventory->reserve($items);
        $this->outbox->publish(\App\Event\Order\OrderPlacedEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    public function pay(Order $order, int $amount): void
    {
        $this->payments->charge($order, $amount);
        $this->apply($order, 'pay');
        $this->outbox->publish(\App\Event\Order\OrderPaidEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    public function ship(Order $order): void
    {
        $this->shipper->ship($order, 'UPS');
        $this->apply($order, 'ship');
        $this->outbox->publish(\App\Event\Order\OrderShippedEvent::class, ['orderId' => $order->getId()]);
        $this->em->flush();
    }

    private function apply(Order $order, string $transition): void
    {
        if (!$this->workflow->can($order, $transition)) {
            throw new \LogicException("Transition '$transition' not allowed");
        }
        $this->workflow->apply($order, $transition);
        $order->setStatus(match ($transition) {
            'place' => OrderStatus::Placed,'pay' => OrderStatus::Paid,'ship' => OrderStatus::Shipped, default => $order->getStatus(),
        });
        $this->em->persist($order);
    }

    public function cancel(Order $order): void
    {
        $this->apply($order, 'cancel');
        $this->publish($order, 'App\\Event\\Order\\OrderCancelledEvent');
    }

    public function refund(Order $order): void
    {
        $this->apply($order, 'refund');
        $this->publish($order, 'App\\Event\\Order\\OrderRefundedEvent');
    }
}
