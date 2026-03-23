<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use App\Message\OrderEventMessage;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */
interface OrderEventMessageHandlerInterface
{
    public function __construct(EventDispatcherInterface $dispatcher);

    public function __invoke(OrderEventMessage $m): void;
}
