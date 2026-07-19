<?php

declare(strict_types=1);

namespace Tests\Unit\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Service\OrderManagementSurfaceContractFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;

final class OrderManagementSurfaceContractFactoryTest extends TestCase
{
    public function testFactoryBuildsCanonicalOrderSurface(): void
    {
        $factory = new OrderManagementSurfaceContractFactory();
        $order = OrderEntity::create('USD', '99.99');

        $surface = $factory->createIndexSurface(
            [$order],
            new FormView(),
            [$order->slug() => new FormView()],
            [$order->slug() => new FormView()],
            [$order->slug() => new FormView()],
        );

        self::assertSame('order', $surface->word);
        self::assertSame('management', $surface->view);
        self::assertSame('order/base.html.twig', $surface->templateName());
        self::assertSame(['left.panel' => 'Create order', 'main.body' => 'Orders', 'right.panel' => 'Summary'], $surface->slotMap);

        $context = $surface->toTemplateContext();
        self::assertArrayHasKey('orders', $context);
        self::assertCount(1, $context['orders']);

        $fallback = $surface->toFallbackData();
        self::assertSame(1, $fallback['orderCount']);
        self::assertSame([$order->slug()], $fallback['orderIds']);
    }
}
