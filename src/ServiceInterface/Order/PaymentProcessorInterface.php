<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;

interface PaymentProcessorInterface
{
    public function createIntentId(): string;

    public function capture(OrderPaymentIntent $intent): OrderTransaction;
}
