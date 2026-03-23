<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Storage;

final class S3ClientFactory
{
    public function __construct(private readonly array $config = [])
    {
    }

    public function create(): object
    {
        if (class_exists(\Aws\S3\S3Client::class)) {
            return new \Aws\S3\S3Client($this->config);
        }

        return new class {
            public function putObject(array $args): array
            {
                return $args;
            }
        };
    }
}
