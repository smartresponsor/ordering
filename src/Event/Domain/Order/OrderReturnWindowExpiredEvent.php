<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Model\Order\OrderReturnPolicy;

final readonly class OrderReturnWindowExpiredEvent
{
    public function __construct(public OrderReturnPolicy $policy)
    {
    }
}
