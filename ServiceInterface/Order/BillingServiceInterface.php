<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Billing\OrderInvoice;
use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\Entity\Order\Order;
use App\Service\Order\OrderPaymentProcessor;
use Doctrine\ORM\EntityManagerInterface;

interface BillingServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        OrderPaymentProcessor $processor,
    );

    public function generateInvoice(Order $order): OrderInvoice;

    public function createPaymentIntent(Order $order, string $amount): OrderPaymentIntent;

    public function capturePayment(OrderPaymentIntent $intent): OrderTransaction;
}
