<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Message\Order\OrderRefundCommand;
use App\Service\Order\RefundService;
use App\Service\Order\TransactionalEventPublisher;

interface OrderRefundCommandHandlerInterface
{
    public function __construct(RefundService $service, TransactionalEventPublisher $publisher);

    public function __invoke(OrderRefundCommand $cmd): void;
}
