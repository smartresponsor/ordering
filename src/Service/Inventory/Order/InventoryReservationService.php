<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Inventory\Order;

use App\Ordering\Entity\Order\OrderStockReservationEntity;
use App\Ordering\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use App\ServiceInterface\Inventory\Order\InventoryGatewayInterface;
use App\ServiceInterface\Inventory\Order\InventoryReservationServiceInterface;

final readonly class InventoryReservationService implements InventoryReservationServiceInterface
{
    public function __construct(
        private InventoryGatewayInterface $gateway,
        private OrderStockReservationRepositoryInterface $repo,
    ) {
    }

    public function reserveOrFail(string $orderId, string $sku, int $qty): OrderStockReservationEntity
    {
        $res = new OrderStockReservationEntity($orderId, $sku, $qty);
        $reservationKey = $this->reservationKey($orderId, $sku);
        $lines = [$sku => $qty];

        if (!$this->gateway->checkAvailability($lines)) {
            $res->markFailed();
            $this->repo->add($res);

            return $res;
        }

        $ok = $this->gateway->reserve($reservationKey, $lines);
        if (!$ok) {
            $res->markFailed();
        }

        $this->repo->add($res);

        return $res;
    }

    public function release(string $orderId, string $sku, int $qty): void
    {
        $existing = $this->repo->findOne($orderId, $sku);
        if (!$existing || OrderStockReservationEntity::STATUS_RESERVED !== $existing->status()) {
            return;
        }

        if ($this->gateway->release($this->reservationKey($orderId, $sku))) {
            $existing->markReleased();
        }
    }

    private function reservationKey(string $orderId, string $sku): string
    {
        return $orderId.':'.$sku;
    }
}
