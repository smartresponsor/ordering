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
        if (isset(self::$views[$orderId])) {
            return self::$views[$orderId];
        }

        try {
            $view = $this->em->find(OrderShipmentView::class, $orderId);
        } catch (\Throwable) {
            $view = null;
        }

        if ($view instanceof OrderShipmentView) {
            self::$views[$orderId] = $view;
        }

        return $view;
    }

    public function save(OrderShipmentView $view): void
    {
        self::$views[$view->orderId()] = $view;
        $this->em->persist($view);
    }
}
