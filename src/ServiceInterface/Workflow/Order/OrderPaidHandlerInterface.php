<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Workflow\Order;

use App\Event\Domain\Order\OrderPaidEvent;

interface OrderPaidHandlerInterface
{

    public function __invoke(OrderPaidEvent $event): void;
}
