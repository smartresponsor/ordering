<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\ServiceInterface\Order\BillingServiceInterface;
use App\ServiceInterface\Order\OrderBillingServiceInterface;
use App\Entity\Order\Billing\OrderInvoice;
use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\Entity\Order;
use App\ValueObject\Billing\Order\InvoiceNumber;
use Doctrine\ORM\EntityManagerInterface;

final class BillingService implements BillingServiceInterface, OrderBillingServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OrderPaymentProcessor $processor,
    ) {
    }

    public function generateInvoice(Order $order): OrderInvoice
    {
        $total = method_exists($order, 'getTotal') ? (string) $order->getTotal() : '0.00';
        $tax = '0.00';
        $invoice = new OrderInvoice($order, InvoiceNumber::of(uniqid('INV-')), $total, $tax);
        $this->em->persist($invoice);
        $this->em->flush();

        return $invoice;
    }

    public function createPaymentIntent(Order $order, string $amount): OrderPaymentIntent
    {
        $intentId = $this->processor->createIntentId();
        $intent = new OrderPaymentIntent($order, $intentId, $amount);
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
