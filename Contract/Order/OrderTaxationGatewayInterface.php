<?php

declare(strict_types=1);

namespace App\Contract\Order;

use App\Entity\Order\Order;

interface OrderTaxationGatewayInterface
{
    public function calculate(Order $order, ?string $countryCode = null): TaxBreakdown;
}
