<?php

declare(strict_types=1);

namespace App\Message\Command;

readonly class FlakyCommand
{
    public function __construct(public string $id)
    {
    }
}
