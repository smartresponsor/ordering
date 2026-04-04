<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Refund\Order;

use App\Message\Legacy\Order\OrderRefundCommand;
use App\Service\Refund\Order\OrderRefundService;
use App\Service\Messaging\Order\TransactionalEventPublisher;

interface OrderRefundCommandHandlerInterface
{
    public function __construct(OrderRefundService $service, TransactionalEventPublisher $publisher);

    public function __invoke(OrderRefundCommand $cmd): void;
}
