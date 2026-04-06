<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderRefundTransaction;

interface OrderRefundTransactionRepositoryInterface
{
    public function add(OrderRefundTransaction $tx): void;
    public function sumByOrder(string $orderId): string;
}
