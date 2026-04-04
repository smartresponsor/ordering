<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

use App\ServiceInterface\Transport\Order\ProviderAdapterInterface;

final class DummyAdapter implements ProviderAdapterInterface
{
    public function __construct(private string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}
