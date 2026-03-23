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

final class WebhookRsaTest extends TestCase
{
    public function testRsaSignVerify()
    {
        $cfg = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $res = openssl_pkey_new($cfg);
        openssl_pkey_export($res, $priv);
        $det = openssl_pkey_get_details($res);
        $pub = $det['key'];

        $s = new WebhookSignerRsa();
        $v = new WebhookVerifierRsa();
        $payload = '{"ok":true}';

        $env = $s->sign($payload, $priv, 'kid-test');
        $this->assertTrue($v->verify($payload, $pub, $env));
    }
}
