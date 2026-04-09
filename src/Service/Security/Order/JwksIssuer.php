<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\JwkRepositoryInterface;
use OpenSSLAsymmetricKey;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final readonly class JwksIssuer
{
    private JwkRepositoryInterface $repo;

    public function __construct(JwkRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function issue(): string
    {
        $keys = [];
        foreach ($this->repo->listActive() as $k) {
            $keys[] = $this->toJwk($k->publicPem(), $k->kid(), $k->alg(), $k->type());
        }

        return json_encode(['keys' => $keys], JSON_PRETTY_PRINT) ?: '{"keys":[]}';
    }

    /** @return array{kty:string,alg:string,use:string,kid:string,n:string,e:string} */
    private function toJwk(string $publicPem, string $kid, string $alg, string $type): array
    {
        $key = openssl_pkey_get_public($publicPem);
        if (!$key instanceof OpenSSLAsymmetricKey) {
            return [
                'kty' => strtoupper($type),
                'alg' => strtoupper($alg),
                'use' => 'sig',
                'kid' => $kid,
                'n' => '',
                'e' => '',
            ];
        }

        $detail = openssl_pkey_get_details($key);
        if (!is_array($detail) || !isset($detail['rsa']) || !is_array($detail['rsa'])) {
            return [
                'kty' => strtoupper($type),
                'alg' => strtoupper($alg),
                'use' => 'sig',
                'kid' => $kid,
                'n' => '',
                'e' => '',
            ];
        }

        $n = base64_encode((string) ($detail['rsa']['n'] ?? ''));
        $e = base64_encode((string) ($detail['rsa']['e'] ?? ''));

        return [
            'kty' => strtoupper($type),
            'alg' => strtoupper($alg),
            'use' => 'sig',
            'kid' => $kid,
            'n' => rtrim(strtr($n, '+/', '-_'), '='),
            'e' => rtrim(strtr($e, '+/', '-_'), '='),
        ];
    }
}
