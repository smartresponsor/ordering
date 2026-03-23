<?php

declare(strict_types=1);

namespace App\Service\Order;

interface ProviderAdapterInterface
{
    public function name(): string;
}
