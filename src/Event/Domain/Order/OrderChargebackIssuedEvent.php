<?php

declare(strict_types=1);

namespace App\Ordering\Event\Domain\Order;

use App\Ordering\Entity\Order\OrderDisputeEntity;

final readonly class OrderChargebackIssuedEvent
{
    public function __construct(public OrderDisputeEntity $dispute)
    {
    }
}
