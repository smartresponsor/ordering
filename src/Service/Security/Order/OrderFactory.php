<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\ServiceInterface\Security\Order\OrderFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderFactory implements OrderFactoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {
    }

    public function create(float $total = 100.00): Order
    {
        $order = new Order();
        if (method_exists($order, 'setTotal')) {
            $order->setTotal($total);
        }
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}
