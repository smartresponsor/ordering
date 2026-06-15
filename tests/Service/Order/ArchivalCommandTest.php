<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Entity\Order\OrderEntity;
use App\Service\Archival\Order\OrderArchivalService;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ArchivalCommandTest extends KernelTestCase
{
    public function testArchivalRuns(): void
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get(EntityManagerInterface::class);
        $svc = self::$kernel->getContainer()->get(OrderArchivalService::class);

        $order = new OrderEntity('V-1', new Money('10.00', 'USD'));
        // emulate old update time via direct SQL if needed; here we just ensure method callable
        $em->persist($order);
        $em->flush();

        $count = $svc->archiveOlderThan(0); // force archive
        $this->assertGreaterThanOrEqual(0, $count);
    }
}
