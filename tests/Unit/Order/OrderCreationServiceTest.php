<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Service\Order\OrderCreationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class OrderCreationServiceTest extends TestCase
{
    public function testCreatesAndPersistsPlacedOrderForCustomerAndVendor(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())
            ->method('persist')
            ->with(self::callback(static fn (mixed $value): bool => $value instanceof OrderEntity));
        $entityManager->expects(self::once())->method('flush');

        $order = (new OrderCreationService($entityManager))->create(' customer-1 ', ' vendor-2 ', 'usd', '125.50');

        self::assertSame('customer-1', $order->getCustomerId());
        self::assertSame('vendor-2', $order->getVendorId());
        self::assertSame('USD', $order->getCurrency());
        self::assertSame('125.50', $order->getGrandTotal());
        self::assertSame('placed', $order->getStatus());
    }

    public function testRejectsMissingCustomerIdentifier(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('persist');
        $entityManager->expects(self::never())->method('flush');

        $this->expectException(\InvalidArgumentException::class);
        (new OrderCreationService($entityManager))->create(' ', 'vendor-2', 'USD', '10.00');
    }
}
