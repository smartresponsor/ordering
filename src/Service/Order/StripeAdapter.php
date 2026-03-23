<?php

declare(strict_types=1);

namespace App\Service\Order;

final class StripeAdapter implements ProviderAdapterInterface
{
    public function name(): string
    {
        return 'stripe';
    }
}
