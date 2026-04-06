<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderShipmentView;
use Doctrine\ORM\EntityManagerInterface;

final class OrderShipmentViewRepository
{
    /** @var array<string, OrderShipmentView> */
    private static array $views = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function find(string $orderId): ?OrderShipmentView
    {
        return self::$views[$orderId] ?? null;
    }

    public function save(OrderShipmentView $view): void
    {
        self::$views[$view->orderId()] = $view;
        $this->em->persist($view);
    }
}
