<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;
use App\ValueObject\OrderStatus;

final class OrderFactory
{
    /** @return list<OrderFactoryProxy> */
    public static function createMany(int $count): array
    {
        $proxies = [];
        $statuses = OrderStatus::cases();

        for ($i = 0; $i < $count; ++$i) {
            $order = new Order();
            $order->setStatus($statuses[array_rand($statuses)]);
            $order->initAudit();
            $proxies[] = new OrderFactoryProxy($order);
        }

        return $proxies;
    }
}
