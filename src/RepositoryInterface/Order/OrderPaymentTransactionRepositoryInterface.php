<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderPaymentTransaction;

interface OrderPaymentTransactionRepositoryInterface
{
    public function add(OrderPaymentTransaction $tx): void;
    public function sumSucceededByOrder(string $orderId): string;
}
