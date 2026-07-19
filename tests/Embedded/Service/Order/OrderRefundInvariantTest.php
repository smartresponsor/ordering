<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Service\Security\Order\OrderService;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderRefundInvariantTest extends KernelTestCase
{
    public function testExceedingRefundFails(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(OrderService::class);

        $order = OrderEntity::create('USD', '100.00');
        $em->persist($order);
        $payment = new OrderPaymentEntity($order, 'pi_1', '60.00', 'USD');
        $em->persist($payment);
        $em->flush();

        $svc->refundPartial($order, new Money('30.00', 'USD'), 'k1');
        $svc->refundPartial($order, new Money('30.00', 'USD'), 'k2');

        $this->expectException(\DomainException::class);
        $svc->refundPartial($order, new Money('10.00', 'USD'), 'k3');
    }
}
