<?php

declare(strict_types=1);

namespace App\Ordering\RepositoryInterface\Order;

use App\Ordering\Entity\Order\OrderRefundTransactionEntity;

interface OrderRefundTransactionRepositoryInterface
{
    public function add(OrderRefundTransactionEntity $tx): void;

    public function sumByOrder(string $orderId): string;
}
