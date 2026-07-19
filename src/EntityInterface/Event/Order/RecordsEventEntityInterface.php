<?php

declare(strict_types=1);

namespace App\Ordering\EntityInterface\Event\Order;

interface RecordsEventEntityInterface
{
    /** @return list<object> */
    public function releaseEvents(): array;
}
