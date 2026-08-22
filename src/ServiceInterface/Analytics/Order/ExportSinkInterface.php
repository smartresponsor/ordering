<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\Analytics\Order;

interface ExportSinkInterface
{
    /**
     * @param array<int,array<string,mixed>> $batch
     */
    public function push(array $batch): void;

    public function flush(): void;
}
