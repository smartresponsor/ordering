<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use App\ServiceInterface\Http\Order\AuditShipInterface;

final readonly class S3AuditShipper implements AuditShipInterface
{
    public function __construct(
        private object $s3,
        private string $projectDir,
        private string $defaultBucket,
        private string $prefix = 'order/',
        private string $sse = 'AES256',
        private string $kmsKey = '',
    ) {
    }

    public function ship(
        string $date,
        ?string $bucket = null,
        ?string $prefix = null,
        ?string $sse = null,
        ?string $kmsKey = null,
    ): array {
        $normalizedDate = 'today' === $date ? date('Ymd') : str_replace('-', '', $date);
        $base = rtrim($this->projectDir, '/\\').'/var/log';
        $candidates = [
            $base.'/order-audit-'.$normalizedDate.'.ndjson.gz',
            $base.'/order-audit-'.$normalizedDate.'.ndjson',
        ];

        $resolved = '';
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                $resolved = $candidate;
                break;
            }
        }

        if ('' === $resolved || !method_exists($this->s3, 'putObject')) {
            return ['uploaded' => 0, 'keys' => []];
        }

        $targetBucket = $bucket ?: $this->defaultBucket;
        $targetPrefix = trim($prefix ?: $this->prefix, '/');
        $targetSse = $sse ?: $this->sse;
        $targetKmsKey = $kmsKey ?: $this->kmsKey;
        $key = $targetPrefix.'/'.basename($resolved);

        $args = [
            'Bucket' => $targetBucket,
            'Key' => $key,
            'SourceFile' => $resolved,
            'ServerSideEncryption' => $targetSse,
        ];
        if ('' !== $targetKmsKey) {
            $args['SSEKMSKeyId'] = $targetKmsKey;
        }

        $result = $this->s3->putObject($args);
        $response = [
            'uploaded' => 1,
            'keys' => [$key],
        ];
        if (is_object($result) && method_exists($result, 'get')) {
            $etag = $result->get('ETag');
            if (is_string($etag) && '' !== $etag) {
                $response['etag'] = $etag;
            }
        }

        return $response;
    }
}
