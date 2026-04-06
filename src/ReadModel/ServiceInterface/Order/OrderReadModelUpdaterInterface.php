<?php

declare(strict_types=1);

namespace App\ReadModel\ServiceInterface\Order;

interface OrderReadModelUpdaterInterface
{
    public function recalc(string $orderId, string $grandTotal): void;
}
