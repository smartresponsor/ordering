<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Payment\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Model\Billing\Order\OrderInvoice;
use App\Ordering\Model\Billing\Order\OrderPaymentIntent;
use App\Ordering\Model\Billing\Order\OrderTransaction;
use App\Ordering\Service\Payment\Order\OrderPaymentProcessor;
use Doctrine\ORM\EntityManagerInterface;

interface BillingServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        OrderPaymentProcessor $processor,
    );

    public function generateInvoice(OrderEntity $OrderEntity): OrderInvoice;

    public function createPaymentIntent(OrderEntity $OrderEntity, string $amount): OrderPaymentIntent;

    public function capturePayment(OrderPaymentIntent $intent): OrderTransaction;
}
