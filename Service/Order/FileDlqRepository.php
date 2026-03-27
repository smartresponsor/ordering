<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\DlqRepositoryInterface;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class FileDlqRepository implements DlqRepositoryInterface
{
    public function __construct(private string $file)
    {
        if (!file_exists($this->file)) {
            touch($this->file);
        }
    }

    public function list(array $filter = []): array
    {
        $out = [];
        $fh = fopen($this->file, 'r');
        if (false === $fh) {
            return $out;
        }

        while (($line = fgets($fh)) !== false) {
            $line = trim($line);
            if ('' === $line) {
                continue;
            }

            $row = json_decode($line, true);
            if (!is_array($row)) {
                continue;
            }

            if (isset($filter['provider']) && (($row['provider'] ?? null) !== $filter['provider'])) {
                continue;
            }

            if (isset($filter['reason']) && !str_contains((string) ($row['reason'] ?? ''), (string) $filter['reason'])) {
                continue;
            }

            $out[] = $row;
        }

        fclose($fh);

        return $out;
    }

    public function get(string $dlqId): ?array
    {
        foreach ($this->list() as $row) {
            if (($row['dlq_id'] ?? '') === $dlqId) {
                return $row;
            }
        }

        return null;
    }

    public function save(array $item): void
    {
        $lines = [];
        $exists = false;

        if (file_exists($this->file)) {
            foreach (file($this->file, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
                $row = json_decode((string) $line, true);
                if (is_array($row) && (($row['dlq_id'] ?? '') === ($item['dlq_id'] ?? ''))) {
                    $lines[] = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
                    $exists = true;
                } else {
                    $trimmed = trim((string) $line);
                    if ('' !== $trimmed) {
                        $lines[] = $trimmed;
                    }
                }
            }
        }

        if (!$exists) {
            $lines[] = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
        }

        file_put_contents($this->file, implode("\n", $lines).(count($lines) > 0 ? "\n" : ''));
    }

    public function delete(string $dlqId): void
    {
        $lines = [];

        if (file_exists($this->file)) {
            foreach (file($this->file, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
                $row = json_decode((string) $line, true);
                if (is_array($row) && (($row['dlq_id'] ?? '') === $dlqId)) {
                    continue;
                }

                $trimmed = trim((string) $line);
                if ('' !== $trimmed) {
                    $lines[] = $trimmed;
                }
            }
        }

        file_put_contents($this->file, implode("\n", $lines).(count($lines) > 0 ? "\n" : ''));
    }
}
