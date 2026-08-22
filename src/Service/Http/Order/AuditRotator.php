<?php

declare(strict_types=1);

namespace App\Ordering\Service\Http\Order;

use App\Ordering\ServiceInterface\Http\Order\AuditRotateInterface;

final readonly class AuditRotator implements AuditRotateInterface
{
    public function __construct(private string $projectDir)
    {
    }

    public function rotate(int $olderThanDays = 0, bool $gzip = true): array
    {
        $path = rtrim($this->projectDir, '/\\').'/var/log/order-audit.ndjson';
        if (!is_file($path)) {
            return [
                'rotated' => 0,
                'manifest' => '',
                'checksum' => '',
            ];
        }

        $stamp = date('Ymd-His');
        $rotated = rtrim($this->projectDir, '/\\').'/var/log/order-audit-'.$stamp.'.ndjson';
        @rename($path, $rotated);

        $finalPath = $rotated;
        if ($gzip && is_file($rotated)) {
            $gzPath = $rotated.'.gz';
            $content = file_get_contents($rotated);
            if (false !== $content) {
                file_put_contents($gzPath, gzencode($content, 9));
                @unlink($rotated);
                $finalPath = $gzPath;
            }
        }

        $manifest = rtrim($this->projectDir, '/\\').'/var/log/order-audit-'.$stamp.'.manifest.json';
        $checksum = is_file($finalPath) ? hash_file('sha256', $finalPath) ?: '' : '';
        $payload = [
            'rotated' => is_file($finalPath) ? 1 : 0,
            'path' => $finalPath,
            'checksum' => $checksum,
            'olderThanDays' => $olderThanDays,
            'gzip' => $gzip,
        ];
        file_put_contents($manifest, (string) json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return [
            'rotated' => (int) ($payload['rotated'] ?? 0),
            'manifest' => $manifest,
            'checksum' => $checksum,
        ];
    }
}
