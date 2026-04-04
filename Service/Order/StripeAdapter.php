<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Order\ProviderAdapterInterface;

final class StripeAdapter implements ProviderAdapterInterface
{
    public function name(): string
    {
        return 'stripe';
    }
}
