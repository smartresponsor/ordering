<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderShipmentView;
use Doctrine\ORM\EntityManagerInterface;

final class OrderShipmentViewRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function find(string $orderId): ?OrderShipmentView { return null; }
    public function save(OrderShipmentView $view): void { $this->em->persist($view); }
}
