<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Model\Billing\Order\OrderInvoice;
use App\Model\Billing\Order\OrderPaymentIntent;
use App\Model\Billing\Order\OrderTransaction;
use App\Ordering\Entity\Order\OrderEntity;
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

    public function generateInvoice(OrderEntity $order): OrderInvoice
    {
        $invoice = new OrderInvoice($order->getId(), $order->getTotal(), $order->getCurrency());
        $this->em->persist($invoice);
        $this->em->flush();

        return $invoice;
    }

    public function createPaymentIntent(OrderEntity $order, string $amount): OrderPaymentIntent
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
