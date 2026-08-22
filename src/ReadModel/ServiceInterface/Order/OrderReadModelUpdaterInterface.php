<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\ServiceInterface\Order;

interface OrderReadModelUpdaterInterface
{
    public function recalc(string $orderId, string $grandTotal): void;
}
