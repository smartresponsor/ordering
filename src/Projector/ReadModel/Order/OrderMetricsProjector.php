<?php

declare(strict_types=1);

namespace App\Ordering\Projector\ReadModel\Order;

final readonly class OrderMetricsProjector
{
    public function __construct(private mixed $connection = null, private mixed $entityManager = null)
    {
    }

    public function rebuildForDay(\DateTimeImmutable $day): int
    {
        return 0;
    }
}
