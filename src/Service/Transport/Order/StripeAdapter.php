<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

use App\ServiceInterface\Transport\Order\ProviderAdapterInterface;

final class StripeAdapter implements ProviderAdapterInterface
{
    public function nameEntity(): string
    {
        return 'stripe';
    }
}
