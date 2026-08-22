<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ServiceInterface\Security\Order\OrderFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderFactory implements OrderFactoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(float $total = 100.00): OrderEntity
    {
        $order = new OrderEntity();
        if (method_exists($order, 'setTotal')) {
            $order->setTotal($total);
        }
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}
