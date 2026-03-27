<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

interface OrderPricingInterface
{
    public function price(float $base, float $rate): float;
}
