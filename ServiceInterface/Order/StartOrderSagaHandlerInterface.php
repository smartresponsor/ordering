<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Message\Command\StartOrderSagaCommand;
use App\Saga\OrderSaga;
use Doctrine\ORM\EntityManagerInterface;

interface StartOrderSagaHandlerInterface
{
    public function __construct(EntityManagerInterface $em, OrderSaga $saga);

    public function __invoke(StartOrderSagaCommand $cmd): void;
}
