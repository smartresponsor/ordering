<?php

declare(strict_types=1);

namespace App\Service\Order\Billing;

use App\Entity\Order\OrderRefundTransaction;
use App\Service\Order\RefundService as BaseRefundService;

final readonly class RefundService
{
    public function __construct(private BaseRefundService $service)
    {
    }

    public function refund(string $orderId, string $amount, ?string $reason = null): OrderRefundTransaction
    {
        return $this->service->refund($orderId, $amount, $reason);
    }
}
