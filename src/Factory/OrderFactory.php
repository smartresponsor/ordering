<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;
use App\ValueObject\OrderStatus;
use Zenstruck\Foundry\ModelFactory;

final class OrderFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $statuses = OrderStatus::cases();

        return ['status' => $statuses[array_rand($statuses)]];
    }

    protected function initialize(): self
    {
        return $this->afterInstantiate(function (Order $order): void { $order->initAudit(); });
    }

    protected static function getClass(): string
    {
        return Order::class;
    }
}
