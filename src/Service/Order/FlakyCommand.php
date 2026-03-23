<?php

declare(strict_types=1);

namespace App\Service\Order;

class FlakyCommand
{
    public function __construct(public readonly string $id)
    {
    }
}
