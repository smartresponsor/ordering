<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class RecalculateOrderPricingCommand
{
    public function __construct(public readonly string $orderId) {
    }
}
