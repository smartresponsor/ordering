<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Message\Domain\Order\OrderDomainMessage;
use Psr\Log\LoggerInterface;

interface OrderDomainMessageHandlerInterface
{
    public function __construct(LoggerInterface $logger);

    public function __invoke(OrderDomainMessage $msg): void;
}
