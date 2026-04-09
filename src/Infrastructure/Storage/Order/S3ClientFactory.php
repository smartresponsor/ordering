<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Order;

final readonly class S3ClientFactory
{
    /** @param array<string, mixed> $config */
    public function __construct(private readonly array $config = [])
    {
    }

    public function create(): object
    {
        if (class_exists('Aws\\S3\\S3Client')) {
            return new ('Aws\\S3\\S3Client')($this->config);
        }

        return new class {
            /** @param array<string, mixed> $args
             * @return array<string, mixed>
             */
            public function putObject(array $args): array
            {
                return $args;
            }
        };
    }
}
