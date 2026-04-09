<?php

declare(strict_types=1);

namespace App\Contract\Domain;

interface RecordsDomainEvents
{
    /** @return list<object> */
    public function releaseEvents(): array;
}
