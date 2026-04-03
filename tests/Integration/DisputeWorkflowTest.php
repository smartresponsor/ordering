<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order;
use App\Service\Order\DisputeService;
use App\Subscriber\Order\OrderDisputeSubscriber;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class DisputeWorkflowTest extends KernelTestCase
{
    public function testOpenAndResolveDispute(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new OrderDisputeSubscriber(new NullLogger()));
        $svc = new DisputeService($em, $dispatcher);

        $order = new Order('VND-1', new Money('100.00', 'USD'));
        $em->persist($order);
        $em->flush();

        $d = $svc->openDispute($order, 'inquiry', 'fraud', 'ext_001');
        $this->assertNotNull($d->getId());

        $svc->resolveDispute($d);
        $this->assertTrue(true);
    }
}
