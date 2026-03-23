<?php

declare(strict_types=1);

namespace Tests\Embedded\Entity\Order\Entity;

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
            \App\Entity\Order\Order::class,
            \App\Entity\Order\OrderItem::class,
            \App\Entity\Order\OrderPayment::class,
            \App\Entity\Order\OrderShipment::class,
        ];
    }
}
