<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\InventoryReservation;
use App\Entity\Order\Order;
use App\Event\Order\StockConsumedEvent;
use App\Event\Order\StockReleasedEvent;
use App\Event\Order\StockReservationFailedEvent;
use App\Event\Order\StockReservedEvent;
use App\ServiceInterface\Order\InventoryGatewayInterface;
use App\ServiceInterface\Order\InventoryServiceInterface;
use App\ServiceInterface\Order\OrderInventoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class InventoryService implements InventoryServiceInterface, OrderInventoryServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $events,
        private readonly InventoryGatewayInterface $gateway,
    ) {
    }

    /** @param array<string,int> $lines sku=>qty */
    public function reserve(Order $order, array $lines, string $key): InventoryReservation
    {
        // idempotent: find existing by key
        $existing = $this->em->getRepository(InventoryReservation::class)->findOneBy(['reservationKey' => $key]);
        if ($existing) {
            return $existing;
        }

        if (!$this->gateway->reserve($key, $lines)) {
            $this->events->dispatch(new StockReservationFailedEvent($order, 'not_enough_stock'));
            throw new \DomainException('Not enough stock');
        }

        $res = new InventoryReservation($order, $key, $lines);

        $this->em->wrapInTransaction(function () use ($res) {
            $this->em->persist($res);
            $this->em->flush();
        });

        $this->events->dispatch(new StockReservedEvent($res));

        return $res;
    }

    public function release(InventoryReservation $res): void
    {
        if (InventoryReservation::STATE_RESERVED !== $res->getState()) {
            return;
        }
        $this->gateway->release($res->getReservationKey());
        $res->markReleased();
        $this->em->flush();
        $this->events->dispatch(new StockReleasedEvent($res));
    }

    public function consume(InventoryReservation $res): void
    {
        if (InventoryReservation::STATE_RESERVED !== $res->getState()) {
            return;
        }
        $this->gateway->consume($res->getReservationKey());
        $res->markConsumed();
        $this->em->flush();
        $this->events->dispatch(new StockConsumedEvent($res));
    }
}
