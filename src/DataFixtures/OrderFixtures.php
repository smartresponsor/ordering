<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Order;
use App\ValueObject\Order\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $order1 = new Order();
        $order1->setStatus(OrderStatus::Draft);

        $order2 = new Order();
        $order2->setStatus(OrderStatus::Placed);

        $manager->persist($order1);
        $manager->persist($order2);
        $manager->flush();
    }
}
