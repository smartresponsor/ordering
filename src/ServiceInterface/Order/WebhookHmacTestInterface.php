<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Service\Order\WebhookSignerHmac;
use App\Service\Order\WebhookVerifierHmac;

interface WebhookHmacTestInterface
{
    public function signer(): WebhookSignerHmac;

    public function verifier(): WebhookVerifierHmac;
}
