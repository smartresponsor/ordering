<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Payment\Order;

use App\Message\Command\Order\OrderPaymentCommand;

interface OrderPaymentCommandHandlerInterface
{
    public function __invoke(OrderPaymentCommand $cmd): void;
}
