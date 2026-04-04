<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Entity\Order\OrderPayment;
use App\ServiceInterface\Order\PaymentGatewayInterface;
use App\ServiceInterface\Order\PaymentProcessorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class PaymentProcessorService implements PaymentProcessorServiceInterface
{
    public function __construct(
        private readonly PaymentGatewayInterface $gateway,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function charge(Order $order, int $amount, string $gatewayName = 'stripe'): OrderPayment
    {
        $reference = $this->gateway->charge($order, $amount);
        $payment = new OrderPayment($order, $gatewayName, $amount);
        $payment->markPaid();
        if (method_exists($payment, 'setReference')) {
            $payment->setReference($reference);
        }
        $this->em->persist($payment);

        return $payment;
    }
}
