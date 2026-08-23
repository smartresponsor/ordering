<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Archival\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final readonly class AuditLog
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function write(string $action, array $data): void
    {
        $row = [
            'ts' => date('c'),
            'action' => $action,
            'data' => $data,
        ];
        file_put_contents($this->file, json_encode($row)."\n", FILE_APPEND);
    }
}
