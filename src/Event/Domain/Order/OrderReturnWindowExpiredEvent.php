<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\OrderReturnPolicy;

final readonly class OrderReturnWindowExpiredEvent
{
    public function __construct(public readonly OrderReturnPolicy $policy) {
    }
}
