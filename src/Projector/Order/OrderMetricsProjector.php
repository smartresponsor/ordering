<?php

declare(strict_types=1);

namespace App\Projector\Order;

final class OrderMetricsProjector
{
    public function __construct(private readonly mixed $connection = null, private readonly mixed $entityManager = null)
    {
    }

    public function rebuildForDay(\DateTimeImmutable $day): int
    {
        return 0;
    }
}
