<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Gateway\InMemoryInventoryGateway;
use App\ServiceInterface\Order\ReservationServiceInterface;
use App\ValueObject\ReservationStatus;

final class ReservationService implements ReservationServiceInterface
{
    public function __construct(private InMemoryInventoryGateway $gateway)
    {
    }

    public function reserve(string $sku, int $qty): ReservationStatus
    {
        if ($qty <= 0) {
            return ReservationStatus::failed('Quantity must be positive.');
        }
        $available = $this->gateway->available($sku);
        if ($available < $qty) {
            return ReservationStatus::failed('Insufficient inventory: available '.$available);
        }
        $this->gateway->reserve($sku, $qty);

        return ReservationStatus::success();
    }

    public function release(string $sku, int $qty): ReservationStatus
    {
        $this->gateway->release($sku, $qty);

        return ReservationStatus::success();
    }
}
