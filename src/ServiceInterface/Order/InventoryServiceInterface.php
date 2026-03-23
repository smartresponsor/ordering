<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\InventoryReservation;
use App\Entity\Order\Order;
use App\Service\Order\InventoryGatewayInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface InventoryServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
        InventoryGatewayInterface $gateway,
    );

    public function reserve(Order $order, array $lines, string $key): InventoryReservation;

    public function release(InventoryReservation $res): void;

    public function consume(InventoryReservation $res): void;
}
