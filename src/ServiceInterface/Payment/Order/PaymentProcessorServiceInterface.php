<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Payment\Order;

use App\Entity\Order;
use App\Entity\Order\OrderPayment;
use App\ServiceInterface\Payment\PaymentGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

interface PaymentProcessorServiceInterface
{
    public function __construct(PaymentGatewayInterface $gateway, EntityManagerInterface $em);

    public function charge(Order $order, int $amount, string $gatewayName = 'stripe'): OrderPayment;
}
