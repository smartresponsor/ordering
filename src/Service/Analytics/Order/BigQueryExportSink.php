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
