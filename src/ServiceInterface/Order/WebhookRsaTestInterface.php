<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use App\Service\Order\WebhookSignerRsa;
use App\Service\Order\WebhookVerifierRsa;

interface WebhookRsaTestInterface
{
    public function signer(): WebhookSignerRsa;

    public function verifier(): WebhookVerifierRsa;
}
