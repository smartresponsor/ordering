<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order;

final readonly class OrderCancelledEvent
{
    public function __construct(public readonly Order $order) {
    }

    public function getName(): string
    {
        return self::class;
    }
}
