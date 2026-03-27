<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderReturnPolicy;
use App\Entity\Order\OrderShipment;
use App\Event\Order\OrderDeliveredEvent;
use App\Event\Order\OrderReturnWindowExpiredEvent;
use App\ServiceInterface\Order\ShipmentServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ShipmentService implements ShipmentServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function markShipped(string $orderId, string $tracking, ?string $carrier = null): void
    {
        $shipment = $this->em->getRepository(OrderShipment::class)->findOneBy(['order' => $orderId]);
        if (!$shipment instanceof OrderShipment) {
            return;
        }

        $shipment->markInTransit();

        if (method_exists($shipment, 'setTrackingCode')) {
            $shipment->setTrackingCode($tracking);
        } else {
            $ref = new \ReflectionObject($shipment);
            foreach (['trackingCode', 'carrier'] as $name) {
                if ($ref->hasProperty($name)) {
                    $prop = $ref->getProperty($name);
                    $prop->setAccessible(true);
                    if ('trackingCode' === $name) {
                        $prop->setValue($shipment, $tracking);
                    } else {
                        $prop->setValue($shipment, $carrier);
                    }
                }
            }
        }

        $this->em->flush();
    }

    public function markDelivered(OrderShipment $shipment, \DateTimeInterface $at): void
    {
        $shipment->markDelivered($at);
        // create/update return policy
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
