<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\OrderPaymentGatewayInterface;
use App\ServiceInterface\Order\OrderShipmentGatewayInterface;
use App\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\Entity\Order;
use App\ServiceInterface\Order\OrderOrchestratorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderOrchestrator implements OrderOrchestratorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OrderPaymentGatewayInterface $paymentGateway,
        private readonly OrderShipmentGatewayInterface $shipmentGateway,
        private readonly OrderTaxationGatewayInterface $taxationGateway,
    ) {
    }

    public function processOrder(Order $order): void
    {
        // 1. Payment
        $this->paymentGateway->initiatePayment($order->getNumber(), (float) $order->getTotalAmount(), $order->getCurrency());

        // 2. Shipment
        $tracking = $this->shipmentGateway->createShipment($order, 'DHL');
        if (method_exists($order, 'assignTracking')) {
            $order->assignTracking($tracking);
        }

        // 3. Taxation
        $breakdown = $this->taxationGateway->calculate($order, 'DE');
        if (method_exists($order, 'setTaxAmount')) {
            $order->setTaxAmount($breakdown->taxAmount);
        }
        if (method_exists($order, 'setTotalAmount')) {
            $order->setTotalAmount($breakdown->total);
        }

        // 4. Finalization
        if (method_exists($order, 'markAsCompleted')) {
            $order->markAsCompleted();
        }
        $this->em->flush();
    }
}
