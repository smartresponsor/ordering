<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use App\ServiceInterface\Analytics\Order\ClickHouseExportSinkInterface;

final readonly class ClickHouseExportSink implements ClickHouseExportSinkInterface
{
    public function __construct(
        private string $endpoint,
        private string $table = 'order_metrics_aggregate_view',
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $batch
     */
    public function push(array $batch): void
    {
        if ([] === $batch) {
            return;
        }

        $payload = implode(
            '
',
            array_map(
                static fn (array $row) => json_encode($row, JSON_UNESCAPED_SLASHES),
                $batch,
            ),
        );

        $query = sprintf('INSERT INTO %s FORMAT JSONEachRow', $this->table);
        $url = rtrim($this->endpoint, '/').'/';
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                ],
                'content' => $payload,
                'ignore_errors' => true,
            ],
        ];

        $target = $url.'?query='.rawurlencode($query);
        $ctx = stream_context_create($opts);
        $resp = @file_get_contents($target, false, $ctx);
        if (false === $resp) {
            $err = error_get_last();

            throw new \RuntimeException('ClickHouse export failed: '.($err['message'] ?? 'unknown error'));
        }

        if (!empty($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $matches)) {
                    $code = (int) $matches[1];
                    if ($code >= 400) {
                        throw new \RuntimeException('ClickHouse HTTP error '.$code.': '.$resp);
                    }

                    break;
                }
            }
        }
    }

    public function flush(): void
    {
    }
}
