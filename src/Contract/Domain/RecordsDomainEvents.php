<?php

declare(strict_types=1);

namespace App\Contract\Domain;

interface RecordsDomainEvents
{
    /** @return array<int,object> */
    public function releaseEvents(): array;
}
