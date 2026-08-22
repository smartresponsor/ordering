<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Workflow\Order;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Contract\Gateway\Order\OrderShipmentGatewayInterface;
use App\Ordering\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ServiceInterface\Workflow\Order\OrderOrchestratorInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderOrchestrator implements OrderOrchestratorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderPaymentGatewayInterface $paymentGateway,
        private OrderShipmentGatewayInterface $shipmentGateway,
        private OrderTaxationGatewayInterface $taxationGateway,
    ) {
    }

    public function processOrder(OrderEntity $order): void
    {
        $this->paymentGateway->charge($order->getId(), $order->getTotalAmount(), ['currency' => $order->getCurrency()]);

        $tracking = $this->shipmentGateway->ship($order->getId(), 'DHL');
        if (method_exists($order, 'assignTracking')) {
            $order->assignTracking($tracking);
        }

        $breakdown = $this->taxationGateway->calculate(
            $order->getNumber(),
            [['price' => $order->getSubtotal(), 'quantity' => 1]],
            ['country' => 'DE', 'currency' => $order->getCurrency()]
        );
        if (method_exists($order, 'setTaxAmount')) {
            $order->setTaxAmount((string) ($breakdown['taxAmount'] ?? '0.00'));
        }
        if (method_exists($order, 'setTotalAmount')) {
            $order->setTotalAmount((string) ($breakdown['total'] ?? $order->getTotalAmount()));
        }

        if (method_exists($order, 'markAsCompleted')) {
            $order->markAsCompleted();
        }
        $this->em->flush();
    }
}
