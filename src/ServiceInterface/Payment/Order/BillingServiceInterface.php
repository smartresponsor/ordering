<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Payment\Order;

use App\Entity\Order\OrderEntity;
use App\Model\Billing\Order\OrderInvoice;
use App\Model\Billing\Order\OrderPaymentIntent;
use App\Model\Billing\Order\OrderTransaction;
use App\Service\Payment\Order\OrderPaymentProcessor;
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
