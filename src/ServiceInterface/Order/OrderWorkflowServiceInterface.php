<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\Service\Inventory\InventoryServiceInterface;
use App\Service\Order\OrderPricing\PriceCalculator;
use App\Service\Outbox\OutboxPublisher;
use App\Service\Payment\PaymentProcessorService;
use App\Service\Shipment\ShipmentProcessorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

interface OrderWorkflowServiceInterface
{
    public function __construct(
        WorkflowInterface $workflow,
        EntityManagerInterface $em,
        ShipmentProcessorService $shipper,
        PaymentProcessorService $payments,
        PriceCalculator $calculator,
        InventoryServiceInterface $inventory,
        OutboxPublisher $outbox,
    );

    /** @param OrderItem[] $items */
    public function place(Order $order, array $items): void;

    public function pay(Order $order, int $amount): void;

    public function ship(Order $order): void;

    public function cancel(Order $order): void;

    public function refund(Order $order): void;
}
