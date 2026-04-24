<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Shipment\Order;

use App\Entity\Order;
use App\Entity\Order\OrderReturnPolicy;
use App\Entity\OrderShipment;
use App\Event\Domain\Order\OrderDeliveredEvent;
use App\Event\Domain\Order\OrderReturnWindowExpiredEvent;
use App\ServiceInterface\Shipment\Order\ShipmentServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class ShipmentService implements ShipmentServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $events,
    ) {
    }

    public function markShipped(string $orderId, string $tracking, ?string $carrier = null): void
    {
        $order = $this->em->getRepository(Order::class)->find($orderId);
        if (!$order instanceof Order) {
            return;
        }

        $shipment = $this->em->getRepository(OrderShipment::class)->findOneBy(['order' => $order]);
        if (!$shipment instanceof OrderShipment) {
            return;
        }

        $shipment->markInTransit();
        $shipment->setTrackingCode($tracking);

        if (null !== $carrier && method_exists($shipment, 'setCarrier')) {
            $shipment->setCarrier($carrier);
        }

        if (method_exists($order, 'assignTracking')) {
            $order->assignTracking($tracking);
        }

        $this->em->flush();
    }

    public function markDelivered(OrderShipment $shipment, \DateTimeInterface $at): void
    {
        $shipment->markDelivered($at);

        $policy = $this->em->getRepository(OrderReturnPolicy::class)->findOneBy(['shipment' => $shipment]);
        if (!$policy) {
            $policy = new OrderReturnPolicy($shipment, 14);
            $this->em->persist($policy);
        } else {
            $policy->setDeliveredRecalculate();
        }

        $this->em->flush();
        $this->events->dispatch(new OrderDeliveredEvent($shipment));
    }

    public function completeShipment(OrderShipment $shipment): void
    {
        $shipment->markCompleted();
        $this->em->flush();
    }

    public function expireIfNeeded(OrderReturnPolicy $policy, \DateTimeInterface $now): bool
    {
        $exp = $policy->getAutoExpireDate();
        if ($exp && $exp <= $now) {
            $this->events->dispatch(new OrderReturnWindowExpiredEvent($policy));

            return true;
        }

        return false;
    }
}
