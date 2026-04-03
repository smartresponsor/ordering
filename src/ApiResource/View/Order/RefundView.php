<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

final class RefundView
{
    public ?string $refundId = null;
    public ?string $status = null;
    public ?int $amountMinor = null;
    public ?string $currency = null;
}
