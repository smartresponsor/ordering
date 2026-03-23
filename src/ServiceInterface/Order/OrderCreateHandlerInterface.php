<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Message\Command\OrderCreateCommand;
use Doctrine\ORM\EntityManagerInterface;

interface OrderCreateHandlerInterface
{
    public function __construct(EntityManagerInterface $em);

    public function __invoke(OrderCreateCommand $cmd): string;
}
