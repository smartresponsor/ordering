<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\Order;
use App\Entity\Order\OrderPayment;
use App\Service\Order\OrderService;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderRefundInvariantTest extends KernelTestCase
{
    public function testExceedingRefundFails(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(OrderService::class);

        $order = new Order('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $payment = new OrderPayment($order, 'pi_1', '60.00', 'USD');
        $em->persist($payment);
        $em->flush();

        $svc->refundPartial($order, new Money('30.00', 'USD'), 'k1');
        $svc->refundPartial($order, new Money('30.00', 'USD'), 'k2');

        $this->expectException(\DomainException::class);
        $svc->refundPartial($order, new Money('10.00', 'USD'), 'k3');
    }
}
