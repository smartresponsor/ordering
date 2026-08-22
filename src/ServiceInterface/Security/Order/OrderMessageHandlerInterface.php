<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Message\OrderMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface OrderMessageHandlerInterface
{
    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $em);

    public function __invoke(OrderMessage $m): void;
}
