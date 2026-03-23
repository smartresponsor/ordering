<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderShipmentView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderShipmentViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderShipmentView::class);
    }

    public function save(OrderShipmentView $view): void
    {
        $em = $this->getEntityManager();
        $em->persist($view);
        $em->flush();
    }
}
