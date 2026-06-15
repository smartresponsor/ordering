<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Shipment\Order;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderShipmentEntity;
use App\Event\Domain\Order\OrderDeliveredEvent;
use App\Event\Domain\Order\OrderReturnWindowExpiredEvent;
use App\Model\Order\OrderReturnPolicy;
use App\Repository\Order\OrderRepository;
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
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($orderId);
        if (!$order instanceof OrderEntity) {
            return;
        }

        $shipment = $this->em->getRepository(OrderShipmentEntity::class)->findOneBy(['order' => $order]);
        if (!$shipment instanceof OrderShipmentEntity) {
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

    public function markDelivered(OrderShipmentEntity $shipment, \DateTimeInterface $at): void
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

    public function completeShipment(OrderShipmentEntity $shipment): void
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
