<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\CryptoServiceInterface;

final class CryptoService implements CryptoServiceInterface
{
    public function __construct(private readonly string $key)
    {
        if (strlen($this->key) < 16) {
            throw new \InvalidArgumentException('Crypto key too short');
        }
    }

    public function encrypt(string $plaintext): string
    {
        $nonce = random_bytes(24);
        $cipher = sodium_crypto_secretbox($plaintext, $nonce, substr(hash('sha256', $this->key, true), 0, 32));

        return base64_encode($nonce.$cipher);
    }

    public function decrypt(string $encoded): string
    {
        $raw = base64_decode($encoded, true);
        if (false === $raw || strlen($raw) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            throw new \RuntimeException('Invalid ciphertext');
        }

        $nonce = substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $plain = sodium_crypto_secretbox_open($cipher, $nonce, substr(hash('sha256', $this->key, true), 0, 32));
        if (false === $plain) {
            throw new \RuntimeException('Invalid ciphertext');
        }

        return $plain;
    }
}
