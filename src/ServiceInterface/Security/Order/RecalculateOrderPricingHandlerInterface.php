<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Message\Command\Order\RecalculateOrderPricingCommand;
use App\Service\Pricing\Order\OrderPricingService;

interface RecalculateOrderPricingHandlerInterface
{
    public function __construct(OrderPricingService $service);

    public function __invoke(RecalculateOrderPricingCommand $cmd): void;
}
