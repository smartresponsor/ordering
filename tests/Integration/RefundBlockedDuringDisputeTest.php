<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Service\Dispute\Order\DisputeService;
use App\Service\Security\Order\OrderService;
use App\Subscriber\Event\Order\OrderDisputeSubscriber;
use App\ValueObject\Pricing\Order\Money;
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

        $order = new OrderEntity('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->persist(new OrderPaymentEntity($order, 'pi_1', '100.00', 'USD'));
        $em->flush();

        $disputes->openDispute($order, 'inquiry', 'fraud', 'ext_001');

        $svc = self::$kernel->getContainer()->get(OrderService::class);
        $this->expectException(\DomainException::class);
        $svc->refundPartial($order, new Money('10.00', 'USD'), 'k1');
    }
}
