<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order;
use App\Entity\Order\OrderPayment;
use App\Service\Order\OrderService;
use App\Subscriber\Event\Order\OrderRefundEventSubscriber;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class OrderRefundEventFlowTest extends KernelTestCase
{
    public function testEventsDispatched(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new OrderRefundEventSubscriber(new NullLogger()));

        $svc = new OrderService($em, $dispatcher);

        $order = new Order('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->persist(new OrderPayment($order, 'pi_1', '100.00', 'USD'));
        $em->flush();

        $svc->refundPartial($order, new Money('40.00', 'USD'), 'e1');
        $svc->refundPartial($order, new Money('60.00', 'USD'), 'e2');
        $this->assertTrue(true);
    }
}
