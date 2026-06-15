<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Order;

use App\Entity\Order\OrderPaymentTransactionEntity;

interface OrderPaymentTransactionRepositoryInterface
{
    public function add(OrderPaymentTransactionEntity $tx): void;

    public function sumSucceededByOrder(string $orderId): string;
}
