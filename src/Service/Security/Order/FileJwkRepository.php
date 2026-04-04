<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\JwkRepositoryInterface;
use App\ValueObject\Security\Order\JwkKey;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class FileJwkRepository implements JwkRepositoryInterface
{
    private string $dir;

    public function __construct(string $dir)
    {
        $this->dir = rtrim($dir, DIRECTORY_SEPARATOR);
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    public function listActive(): array
    {
        $out = [];
        foreach (glob($this->dir.DIRECTORY_SEPARATOR.'*.json') as $file) {
            $data = json_decode((string) file_get_contents($file), true);
            if (!is_array($data)) {
                continue;
            }
            if (!($data['active'] ?? false)) {
                continue;
            }
            $out[] = new JwkKey($data['kid'], $data['alg'], $data['type'], $data['public_pem'], $data['private_pem'] ?? null, (bool) $data['active']);
        }

        return $out;
    }

    public function findByKid(string $kid): ?JwkKey
    {
        $file = $this->dir.DIRECTORY_SEPARATOR.$kid.'.json';
        if (!file_exists($file)) {
            return null;
        }
        $data = json_decode((string) file_get_contents($file), true);
        if (!is_array($data)) {
            return null;
        }

        return new JwkKey($data['kid'], $data['alg'], $data['type'], $data['public_pem'], $data['private_pem'] ?? null, (bool) $data['active']);
    }

    public function save(JwkKey $key): void
    {
        $file = $this->dir.DIRECTORY_SEPARATOR.$key->kid().'.json';
        $row = [
            'kid' => $key->kid(),
            'alg' => $key->alg(),
            'type' => $key->type(),
            'public_pem' => $key->publicPem(),
            'private_pem' => $key->privatePem(),
            'active' => $key->active(),
        ];
        file_put_contents($file, json_encode($row, JSON_PRETTY_PRINT));
    }

    public function deactivate(string $kid): void
    {
        $key = $this->findByKid($kid);
        if (!$key) {
            return;
        }
        $inactive = new JwkKey($key->kid(), $key->alg(), $key->type(), $key->publicPem(), $key->privatePem(), false);
        $this->save($inactive);
    }
}
