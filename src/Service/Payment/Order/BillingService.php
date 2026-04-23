<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Entity\Order;
use App\Entity\Order\Billing\OrderInvoice;
use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\ServiceInterface\Payment\Order\BillingServiceInterface;
use App\ServiceInterface\Payment\Order\OrderBillingServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class BillingService implements BillingServiceInterface, OrderBillingServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderPaymentProcessor $processor,
    ) {
    }

    public function generateInvoice(Order $order): OrderInvoice
    {
        $invoice = new OrderInvoice($order->getId(), $order->getTotal(), $order->getCurrency());
        $this->em->persist($invoice);
        $this->em->flush();

        return $invoice;
    }

    public function createPaymentIntent(Order $order, string $amount): OrderPaymentIntent
    {
        $intentId = $this->processor->createIntentId();
        $intent = new OrderPaymentIntent($order->getId(), $amount, $order->getCurrency(), 'stripe', $intentId);
        $this->em->persist($intent);
        $this->em->flush();

        return $intent;
    }

    public function capturePayment(OrderPaymentIntent $intent): OrderTransaction
    {
        $txn = $this->processor->capture($intent);
        $this->em->persist($txn);
        $this->em->flush();

        return $txn;
    }
}
