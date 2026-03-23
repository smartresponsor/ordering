<?php

declare(strict_types=1);

namespace Tests\Embedded\Entity\Order;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>.
 */
final class OrderSmokeTest
{
    /**
     * @return list<class-string>
     */
    public static function subjectClasses(): array
    {
        return [
            Order::class,
            OrderItem::class,
            OrderPayment::class,
            OrderShipment::class,
        ];
    }
}
