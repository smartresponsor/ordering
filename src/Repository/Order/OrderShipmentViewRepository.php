<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Entity\OrderShipmentView;
use App\Ordering\RepositoryInterface\Order\OrderShipmentViewRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderShipmentViewRepository implements OrderShipmentViewRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function find(string $orderId): ?OrderShipmentView
    {
        $view = $this->em->find(OrderShipmentView::class, $orderId);

        return $view instanceof OrderShipmentView ? $view : null;
    }

    public function save(OrderShipmentView $view): void
    {
        $this->em->persist($view);
    }
}
