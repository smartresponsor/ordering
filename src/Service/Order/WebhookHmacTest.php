<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

use PHPUnit\Framework\TestCase;

final class WebhookHmacTest extends TestCase
{
    public function testHmacSignVerify()
    {
        $s = new WebhookSignerHmac();
        $v = new WebhookVerifierHmac();
        $payload = '{"ok":true}';
        $secret = 'x';
        $sig = $s->sign($payload, $secret);
        $this->assertTrue($v->verify($payload, $secret, $sig));
        $this->assertFalse($v->verify($payload, 'y', $sig));
    }
}
