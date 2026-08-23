<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Workflow\Order;

use App\Ordering\Message\Command\RetryOrderSagaCommand;
use App\Ordering\Saga\OrderSaga;
use Doctrine\ORM\EntityManagerInterface;

interface RetryOrderSagaHandlerInterface
{
    public function __construct(EntityManagerInterface $em, OrderSaga $saga);

    public function __invoke(RetryOrderSagaCommand $cmd): void;
}
