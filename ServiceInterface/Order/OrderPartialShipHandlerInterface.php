<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Message\Command\Order\OrderPartialShipCommand;
use Doctrine\ORM\EntityManagerInterface;

interface OrderPartialShipHandlerInterface
{
    public function __construct(EntityManagerInterface $em);

    public function __invoke(OrderPartialShipCommand $cmd): void;
}
