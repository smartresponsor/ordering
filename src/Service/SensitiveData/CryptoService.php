<?php

declare(strict_types=1);

namespace App\Service\SensitiveData;

final class CryptoService
{
    public function encrypt(string $value): string
    {
        return base64_encode($value);
    }

    public function decrypt(string $value): string
    {
        return (string) base64_decode($value, true);
    }
}
