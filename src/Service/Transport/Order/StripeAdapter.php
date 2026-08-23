<?php

declare(strict_types=1);

namespace App\Ordering\Service\Transport\Order;

use App\Ordering\ServiceInterface\Transport\Order\ProviderAdapterInterface;

final class StripeAdapter implements ProviderAdapterInterface
{
    public function nameEntity(): string
    {
        return 'stripe';
    }
}
