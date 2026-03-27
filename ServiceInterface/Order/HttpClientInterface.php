<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

interface HttpClientInterface
{
    /**
     * @return array{0:int,1:array<string,mixed>}
     */
    public function get(string $path, array $headers = []): array;

    /**
     * @param array<string,mixed> $payload
     * @param list<string>        $headers
     *
     * @return array{0:int,1:array<string,mixed>}
     */
    public function post(string $path, array $payload = [], array $headers = []): array;
}
