<?php

declare(strict_types=1);

namespace App\Ordering\Message\Command;

readonly class FlakyCommand
{
    public function __construct(public string $id)
    {
    }
}
