<?php

declare(strict_types=1);

namespace App\Ordering\EntityInterface\Order;

interface RecordsEventEntityInterface
{
    /** @return list<object> */
    public function releaseEvents(): array;
}
