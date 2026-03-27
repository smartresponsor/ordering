<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\JwkRepositoryInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class JwksIssuer
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

        return json_encode(['keys' => $keys], JSON_PRETTY_PRINT);
    }

    private function toJwk(string $publicPem, string $kid, string $alg, string $type): array
    {
        // RSA only for demo
        $res = openssl_pkey_get_public($publicPem);
        $detail = openssl_pkey_get_details($res);
        $n = base64_encode($detail['rsa']['n']);
        $e = base64_encode($detail['rsa']['e']);

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
