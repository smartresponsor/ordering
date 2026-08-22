<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Transport\Order;

interface ProviderAdapterInterface
{
    public function nameEntity(): string;
}
