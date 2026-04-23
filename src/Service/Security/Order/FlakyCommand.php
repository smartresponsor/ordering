<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

readonly class FlakyCommand
{
    public function __construct(public string $id)
    {
    }
}
