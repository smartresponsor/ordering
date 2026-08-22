<?php

declare(strict_types=1);

namespace App\Ordering\Service\Transport\Order;

use App\Ordering\ServiceInterface\Transport\Order\ProviderAdapterInterface;

final readonly class DummyAdapter implements ProviderAdapterInterface
{
    public function __construct(private string $nameEntity)
    {
    }

    public function nameEntity(): string
    {
        return $this->nameEntity;
    }
}
