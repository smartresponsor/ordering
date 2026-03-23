<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Contract\Order\OrderPaymentGatewayInterface;
use App\Contract\Order\OrderShipmentGatewayInterface;
use App\Contract\Order\OrderTaxationGatewayInterface;
use App\Entity\Order\Order;
use Doctrine\ORM\EntityManagerInterface;

interface OrderOrchestratorInterface
{
    public function __construct(
        EntityManagerInterface $em,
        OrderPaymentGatewayInterface $paymentGateway,
        OrderShipmentGatewayInterface $shipmentGateway,
        OrderTaxationGatewayInterface $taxationGateway,
    );

    public function processOrder(Order $order): void;
}
