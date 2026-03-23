<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\OrderStockReservation;
use App\RepositoryInterface\Order\OrderStockReservationRepositoryInterface;
use App\ServiceInterface\Order\InventoryGatewayInterface;
use App\ServiceInterface\Order\InventoryReservationServiceInterface;

final class InventoryReservationService implements InventoryReservationServiceInterface
{
    public function __construct(
        private InventoryGatewayInterface $gateway,
        private OrderStockReservationRepositoryInterface $repo,
    ) {
    }

    public function reserveOrFail(string $orderId, string $sku, int $qty): OrderStockReservation
    {
        $res = new OrderStockReservation($orderId, $sku, $qty);
        if (!$this->gateway->checkAvailable($sku, $qty)) {
            $res->markFailed();
            $this->repo->add($res);

            return $res;
        }
        $ok = $this->gateway->reserve($orderId, $sku, $qty);
        if (!$ok) {
            $res->markFailed();
        }
        $this->repo->add($res);

        return $res;
    }

    public function release(string $orderId, string $sku, int $qty): void
    {
        $existing = $this->repo->findOne($orderId, $sku);
        if ($existing && OrderStockReservation::STATUS_RESERVED === $existing->status()) {
            if ($this->gateway->release($orderId, $sku, $qty)) {
                $existing->markReleased();
            }
        }
    }
}
