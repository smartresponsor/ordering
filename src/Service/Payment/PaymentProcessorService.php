<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderPaymentEntity;
use App\ServiceInterface\Payment\PaymentGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PaymentProcessorService
{
    public function __construct(private PaymentGatewayInterface $gateway, private EntityManagerInterface $em)
    {
    }

    public function charge(OrderEntity $order, int $amount, string $gatewayName = 'stripe'): OrderPaymentEntity
    {
        $ref = $this->gateway->charge($order, $amount);
        $p = new OrderPaymentEntity($order, $gatewayName, $amount);
        $p->markPaid();
        $this->em->persist($p);

        return $p;
    }
}
