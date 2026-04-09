<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Workflow\Order;

use App\Event\Domain\Order\OrderRefundedEvent;

interface OrderRefundedHandlerInterface
{

    public function __invoke(OrderRefundedEvent $event): void;
}
