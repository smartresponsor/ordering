<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Payment\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Ordering\ServiceInterface\Payment\Order\PaymentGatewayInterface;
use App\Ordering\ServiceInterface\Payment\Order\PaymentProcessorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PaymentProcessorService implements PaymentProcessorServiceInterface
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private EntityManagerInterface $em,
    ) {
    }

    public function charge(OrderEntity $order, int $amount, string $gatewayName = 'stripe'): OrderPaymentEntity
    {
        $reference = $this->gateway->charge($order, $amount);
        $payment = new OrderPaymentEntity($order, $gatewayName, $amount);
        $payment->markPaid();
        if (method_exists($payment, 'setReference')) {
            $payment->setReference($reference);
        }
        $this->em->persist($payment);

        return $payment;
    }
}
