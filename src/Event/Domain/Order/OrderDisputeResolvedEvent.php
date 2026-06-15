<?php

declare(strict_types=1);

namespace App\Event\Domain\Order;

use App\Entity\Order\OrderDisputeEntity;

final readonly class OrderDisputeResolvedEvent
{
    public function __construct(public OrderDisputeEntity $dispute)
    {
    }
}
