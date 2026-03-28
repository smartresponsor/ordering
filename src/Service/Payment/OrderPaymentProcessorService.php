<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Order;
use App\Entity\OrderPayment;
use App\ServiceInterface\Payment\PaymentGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderPaymentProcessorService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private EntityManagerInterface $em,
    ) {
    }

    public function charge(Order $order, int $amount, string $gatewayName = 'stripe'): OrderPayment
    {
        $reference = $this->gateway->charge($order, $amount);
        $payment = $order->applyPayment(number_format($amount / 100, 2, '.', ''), $reference, true, $gatewayName);
        $this->em->persist($payment);

        return $payment;
    }
}
