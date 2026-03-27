<?php

declare(strict_types=1);

namespace App\Service\Order\Http;

use App\ServiceInterface\Order\Http\AuditShipInterface;

final class S3AuditShipper implements AuditShipInterface
{
    public function __construct(
        private readonly object $s3,
        private readonly string $projectDir,
        private readonly string $defaultBucket,
        private readonly string $prefix = 'order/',
        private readonly string $sse = 'AES256',
        private readonly string $kmsKey = '',
    ) {
    }

    public function ship(string $path): void
    {
        $resolved = $path;
        if (!str_starts_with($resolved, '/') && !preg_match('~^[A-Za-z]:[\\/]~', $resolved)) {
            $resolved = rtrim($this->projectDir, '/\\').'/'.ltrim($resolved, '/\\');
        }

        if (!is_file($resolved)) {
            return;
        }

        if (method_exists($this->s3, 'putObject')) {
            $args = [
                'Bucket' => $this->defaultBucket,
                'Key' => trim($this->prefix, '/').'/'.basename($resolved),
                'SourceFile' => $resolved,
                'ServerSideEncryption' => $this->sse,
            ];
            if ('' !== $this->kmsKey) {
                $args['SSEKMSKeyId'] = $this->kmsKey;
            }
            $this->s3->putObject($args);
        }
    }
}
