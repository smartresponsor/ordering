<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Service\Security\Order\OrderService;
use App\Ordering\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderPartialRefundIdempotencyTest extends KernelTestCase
{
    public function testRefundPartialIsIdempotent(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(OrderService::class);

        $order = new OrderEntity('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $key = 'rf-123';
        $l1 = $svc->refundPartial($order, new Money('10.00', 'USD'), $key);
        $l2 = $svc->refundPartial($order, new Money('10.00', 'USD'), $key);

        self::assertSame($l1->getKey(), $l2->getKey());
        self::assertSame($l1->getAmount(), $l2->getAmount());
    }
}
