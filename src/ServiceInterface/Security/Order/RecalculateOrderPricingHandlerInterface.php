<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Message\Command\Order\RecalculateOrderPricingCommand;

interface RecalculateOrderPricingHandlerInterface
{
    public function __invoke(RecalculateOrderPricingCommand $cmd): void;
}
