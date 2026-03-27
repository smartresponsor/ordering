<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Gateway\InMemoryInventoryGateway;
use App\ValueObject\ReservationStatus;

interface ReservationServiceInterface
{
    public function __construct(InMemoryInventoryGateway $gateway);

    public function reserve(string $sku, int $qty): ReservationStatus;

    public function release(string $sku, int $qty): ReservationStatus;
}
