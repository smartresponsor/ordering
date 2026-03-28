<?php

declare(strict_types=1);

namespace App\Service\Demo;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

final class OrderDemoDataService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function purge(): void
    {
        foreach (['order_shipment', 'order_refund', 'order_payment', 'orders'] as $table) {
            $this->em->getConnection()->executeStatement(sprintf('DELETE FROM %s', $table));
        }
    }

    public function load(int $count = 12): int
    {
        $faker = Factory::create();
        $currencies = ['USD', 'EUR', 'GBP'];

        for ($i = 0; $i < $count; ++$i) {
            $total = number_format($faker->randomFloat(2, 25, 350), 2, '.', '');
            $order = new Order($faker->randomElement($currencies), $total);

            if ($i % 4 >= 1) {
                $payment = $order->applyPayment($total, sprintf('demo-pay-%02d', $i));
                $this->em->persist($payment);
            }

            if ($i % 4 >= 2) {
                $shipment = $order->ship($faker->randomElement(['UPS', 'DHL']), sprintf('trk-%02d', $i), 'Fixture shipment');
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
