<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Message\Command\Order\OrderRefundCommand;
use App\Ordering\Service\Refund\Order\RefundPolicyService;
use Doctrine\ORM\EntityManagerInterface;

interface OrderRefundHandlerInterface
{
    public function __construct(EntityManagerInterface $em, RefundPolicyService $policy);

    public function __invoke(OrderRefundCommand $c): void;
}
