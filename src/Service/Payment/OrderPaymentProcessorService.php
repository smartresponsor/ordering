<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderPaymentEntity;
use App\ServiceInterface\Payment\PaymentGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderPaymentProcessorService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private EntityManagerInterface $em,
    ) {
    }

    public function charge(OrderEntity $order, int $amount, string $gatewayName = 'stripe'): OrderPaymentEntity
    {
        $reference = $this->gateway->charge($order, $amount);
        $payment = $order->applyPayment(number_format($amount / 100, 2, '.', ''), $reference, true, $gatewayName);
        $this->em->persist($payment);

        return $payment;
    }
}
