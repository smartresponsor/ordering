<?php

declare(strict_types=1);

namespace App\Service\Demo;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

final readonly class OrderDemoDataService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function purge(): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $orders = $repo->findAll();
        foreach ($orders as $order) {
            if ($order instanceof OrderEntity) {
                $this->em->remove($order);
            }
        }

        $this->em->flush();
    }

    public function load(int $count = 12): int
    {
        $faker = Factory::create();
        $currencies = ['USD', 'EUR', 'GBP'];
        $carriers = ['UPS', 'DHL'];

        for ($i = 0; $i < $count; ++$i) {
            $total = number_format($faker->randomFloat(2, 25, 350), 2, '.', '');
            $order = new OrderEntity($faker->randomElement($currencies), $total);

            if ($i % 4 >= 1) {
                $payment = $order->applyPayment($total, sprintf('demo-pay-%02d', $i));
                $this->em->persist($payment);
            }

            if ($i % 4 >= 2) {
                $shipment = $order->ship($carriers[$i % 2], sprintf('trk-%02d', $i), 'Fixture shipment');
                $this->em->persist($shipment);
            }

            if (3 === $i % 4) {
                $refund = $order->refund(number_format((float) $total / 2, 2, '.', ''), 'Fixture partial refund');
                $this->em->persist($refund);
            }

            $this->em->persist($order);
        }

        $this->em->flush();

        return $count;
    }
}
