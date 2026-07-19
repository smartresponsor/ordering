<?php

declare(strict_types=1);

namespace App\Ordering\RepositoryInterface\Order;

use App\Ordering\Entity\Order\OrderPaymentTransactionEntity;

interface OrderPaymentTransactionRepositoryInterface
{
    public function add(OrderPaymentTransactionEntity $tx): void;

    public function sumSucceededByOrder(string $orderId): string;
}
