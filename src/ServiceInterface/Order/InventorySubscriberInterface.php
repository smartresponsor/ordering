<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface InventorySubscriberInterface
{
    public function onPlaced(object $event): void;

    public function onCancelled(object $event): void;

    public function onRefunded(object $event): void;
}
