<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\ExportSinkInterface;

final class BigQueryExportSink implements ExportSinkInterface
{
    public function __construct(private readonly string $targetPath)
    {
    }

    public function push(array $batch): void
    {
        // Write NDJSON batch locally for later load to BigQuery
        $file = rtrim($this->targetPath, '/').'/metrics_export.ndjson';
        $fh = fopen($file, 'a');
        foreach ($batch as $row) {
            fwrite($fh, json_encode($row)."\n");
        }
        fclose($fh);
    }

    public function flush(): void
    {
    }
}
