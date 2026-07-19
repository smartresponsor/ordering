<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Ordering\Entity\Order\OrderEntity;
use App\ValueObject\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $orders = [
            ['USD', '0.00', OrderStatus::Draft],
            ['USD', '149.99', OrderStatus::Placed],
            ['EUR', '249.50', OrderStatus::Paid],
            ['UAH', '799.00', OrderStatus::Shipped],
        ];

        foreach ($orders as [$currency, $grandTotal, $status]) {
            $order = OrderEntity::create($currency, $grandTotal);
            $order->setStatus($status->value);
            $manager->persist($order);
        }

        $manager->flush();
    }
}
