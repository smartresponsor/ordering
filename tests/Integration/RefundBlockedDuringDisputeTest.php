<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\Order;
use App\Entity\Order\OrderPayment;
use App\Service\Order\DisputeService;
use App\Service\Order\OrderService;
use App\Subscriber\Order\OrderDisputeSubscriber;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class RefundBlockedDuringDisputeTest extends KernelTestCase
{
    public function testRefundIsBlocked(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new OrderDisputeSubscriber(new NullLogger()));
        $disputes = new DisputeService($em, $dispatcher);

        $order = new Order('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->persist(new OrderPayment($order, 'pi_1', '100.00', 'USD'));
        $em->flush();

        $disputes->openDispute($order, 'inquiry', 'fraud', 'ext_001');

        $svc = self::$kernel->getContainer()->get(OrderService::class);
        $this->expectException(\DomainException::class);
        $svc->refundPartial($order, new Money('10.00', 'USD'), 'k1');
    }
}
