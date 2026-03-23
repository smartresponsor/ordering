<?php

declare(strict_types=1);

namespace App\Monitoring;

final class MetricsRegistry
{
    public function __construct(private readonly string $path)
    {
    }

    /** @param array<string, scalar> $labels */
    public function increment(string $metric, array $labels = []): void
    {
        $line = $metric;
        if ([] !== $labels) {
            $pairs = [];
            foreach ($labels as $key => $value) {
                $pairs[] = sprintf('%s="%s"', $key, addslashes((string) $value));
            }
            $line .= '{'.implode(',', $pairs).'}';
        }
        $line .= " 1\n";

        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        file_put_contents($this->path, $line, FILE_APPEND);
    }
}
