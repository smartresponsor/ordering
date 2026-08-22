<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ReadModel\Repository\OrderReadRepository;
use App\Ordering\ReadModel\Service\CustomerOrderReadService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

final class CustomerOrderReadServiceTest extends TestCase
{
    public function testCustomerScopedProjectionAndLookup(): void
    {
        $order = new OrderEntity('ORD-CUSTOMER-1', '125.50', 'USD', 'customer-1');
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('findBy')->willReturn([$order]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);
        $service = new CustomerOrderReadService(new OrderReadRepository($entityManager));

        $items = $service->listForCustomer('customer-1');

        self::assertCount(1, $items);
        self::assertSame($order->slug(), $items[0]->reference);
        self::assertSame('ORD-CUSTOMER-1', $items[0]->number);
        self::assertSame('USD', $items[0]->currency);
        self::assertSame('125.50', $items[0]->grandTotal);
        self::assertNotNull($service->findForCustomer('customer-1', 'ORD-CUSTOMER-1'));
        self::assertNull($service->findForCustomer('customer-1', 'ORD-OTHER'));
    }

    public function testBlankCustomerCannotReadOrders(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('getRepository');
        $service = new CustomerOrderReadService(new OrderReadRepository($entityManager));

        self::assertSame([], $service->listForCustomer(''));
        self::assertNull($service->findForCustomer('', 'ORD-1'));
    }
}
