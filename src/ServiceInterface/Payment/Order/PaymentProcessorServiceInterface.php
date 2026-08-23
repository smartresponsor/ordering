<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Payment\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use Doctrine\ORM\EntityManagerInterface;

interface PaymentProcessorServiceInterface
{
    public function __construct(PaymentGatewayInterface $gateway, EntityManagerInterface $em);

    public function charge(OrderEntity $OrderEntity, int $amount, string $gatewayName = 'stripe'): OrderPaymentEntity;
}
