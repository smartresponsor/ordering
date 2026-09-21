<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use App\Ordering\ServiceInterface\Analytics\Order\BigQueryExportSinkInterface;

final readonly class BigQueryExportSink implements BigQueryExportSinkInterface
{
    public function __construct(private string $targetPath)
    {
    }

    public function push(array $batch): void
    {
        if ([] === $batch) {
            return;
        }

        if (!is_dir($this->targetPath) && !mkdir($this->targetPath, 0775, true) && !is_dir($this->targetPath)) {
            throw new \RuntimeException(sprintf('Unable to create BigQuery export directory "%s".', $this->targetPath));
        }

        $file = rtrim($this->targetPath, '/\\').DIRECTORY_SEPARATOR.'metrics_export.ndjson';
        $handle = fopen($file, 'a');
        if (false === $handle) {
            throw new \RuntimeException(sprintf('Unable to open BigQuery export file "%s".', $file));
        }

        try {
            foreach ($batch as $row) {
                $line = json_encode($row, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
                if (false === fwrite($handle, $line)) {
                    throw new \RuntimeException(sprintf('Unable to write BigQuery export file "%s".', $file));
                }
            }
        } finally {
            fclose($handle);
        }
    }

    public function flush(): void
    {
    }
}
