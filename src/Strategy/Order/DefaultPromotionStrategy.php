<?php

declare(strict_types=1);

namespace App\Strategy\Order;

final class DefaultPromotionStrategy
{
    public function discount(string $subtotal): string
    {
        return '0.00';
    }
}
