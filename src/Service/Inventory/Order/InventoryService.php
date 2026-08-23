<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Inventory\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderStockReservationEntity;
use App\Ordering\Event\Domain\Order\StockConsumedEvent;
use App\Ordering\Event\Domain\Order\StockReleasedEvent;
use App\Ordering\Event\Domain\Order\StockReservationFailedEvent;
use App\Ordering\Event\Domain\Order\StockReservedEvent;
use App\Ordering\ServiceInterface\Inventory\Order\InventoryGatewayInterface;
use App\Ordering\ServiceInterface\Inventory\Order\InventoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class InventoryService implements InventoryServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $events,
        private InventoryGatewayInterface $gateway,
    ) {
    }

    /** @param array<string,int> $lines sku=>qty */
    public function reserve(OrderEntity $order, array $lines, string $key): OrderStockReservationEntity
    {
        // idempotent: find existing by key
        $existing = $this->em->getRepository(OrderStockReservationEntity::class)->findOneBy(['reservationKey' => $key]);
        if ($existing) {
            return $existing;
        }

        if (!$this->gateway->reserve($key, $lines)) {
            $this->events->dispatch(new StockReservationFailedEvent($order, 'not_enough_stock'));
            throw new \DomainException('Not enough stock');
        }

        $res = new OrderStockReservationEntity($order, $key, $lines);

        $this->em->wrapInTransaction(function () use ($res) {
            $this->em->persist($res);
            $this->em->flush();
        });

        $this->events->dispatch(new StockReservedEvent($res));

        return $res;
    }

    public function release(OrderStockReservationEntity $res): void
    {
        if (OrderStockReservationEntity::STATE_RESERVED !== $res->getState()) {
            return;
        }
        $this->gateway->release($res->getReservationKey());
        $res->markReleased();
        $this->em->flush();
        $this->events->dispatch(new StockReleasedEvent($res));
    }

    public function consume(OrderStockReservationEntity $res): void
    {
        if (OrderStockReservationEntity::STATE_RESERVED !== $res->getState()) {
            return;
        }
        $this->gateway->consume($res->getReservationKey());
        $res->markConsumed();
        $this->em->flush();
        $this->events->dispatch(new StockConsumedEvent($res));
    }
}
