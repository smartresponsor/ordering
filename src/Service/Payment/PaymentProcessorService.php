<?php

declare(strict_types=1);

namespace App\Ordering\Service\Payment;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Ordering\ServiceInterface\Payment\PaymentGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PaymentProcessorService
{
    public function __construct(private PaymentGatewayInterface $gateway, private EntityManagerInterface $em)
    {
    }

    public function charge(OrderEntity $order, int $amount, string $gatewayName = 'stripe'): OrderPaymentEntity
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $externalRef = $this->gateway->charge($order, $amount);
        $normalizedAmount = number_format($amount / 100, 2, '.', '');
        $payment = $order->applyPayment(
            $normalizedAmount,
            $externalRef,
            bccomp($normalizedAmount, $order->getGrandTotal(), 2) < 0,
            $gatewayName,
        );
        $this->em->persist($payment);
        $this->em->persist($order);

        return $payment;
    }
}
