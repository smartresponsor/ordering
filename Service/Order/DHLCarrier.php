<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\CarrierInterface;
use App\ServiceInterface\Order\DHLCarrierInterface;

final class DHLCarrier implements CarrierInterface, DHLCarrierInterface
{
    public function ship(string $orderId, string $carrierCode, array $context = []): string
    {
        return 'JD'.strtoupper(substr(hash('sha1', $orderId.$carrierCode.microtime()), 0, 16));
    }
}
