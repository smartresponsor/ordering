<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Order;
use App\Entity\OrderPayment;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PaymentProcessorService
{
    public function __construct(private PaymentGatewayInterface $gateway, private EntityManagerInterface $em)
    {
    }

    public function charge(Order $order, int $amount, string $gatewayName = 'stripe'): OrderPayment
    {
        $ref = $this->gateway->charge($order, $amount);
        $p = new OrderPayment($order, $gatewayName, $amount);
        $p->markPaid();
        $this->em->persist($p);

        return $p;
    }
}
