<?php

declare(strict_types=1);

namespace App\Event\Order;

use App\Entity\Order\OrderDispute;

final readonly class OrderDisputeOpenedEvent
{
    public function __construct(public OrderDispute $dispute)
    {
    }
}
