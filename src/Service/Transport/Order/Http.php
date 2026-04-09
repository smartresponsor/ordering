<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

final readonly class Http
{
    /**
     * @param array<string, string> $headers
     * @param array<string, mixed>|string|null $payload
     *
     * @return array{0:int,1:string}
     */
    public static function req(
        string $method,
        string $url,
        array $headers = [],
        array|string|null $payload = null,
        int $timeout = 10,
    ): array {
        $curl = curl_init($url);
        if (false === $curl) {
            throw new \RuntimeException('Unable to initialize HTTP request.');
        }

        $normalizedHeaders = [];
        foreach ($headers as $name => $value) {
            $normalizedHeaders[] = $name.': '.$value;
        }

        $body = null;
        if (is_array($payload)) {
            $body = json_encode($payload, JSON_THROW_ON_ERROR);
            $normalizedHeaders[] = 'Content-Type: application/json';
        } elseif (is_string($payload)) {
            $body = $payload;
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => $normalizedHeaders,
        ];

        if (null !== $body) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $code = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if (false === $response) {
            $error = curl_error($curl);
            curl_close($curl);

            throw new \RuntimeException('HTTP request failed: '.$error);
        }

        curl_close($curl);

        return [$code, (string) $response];
    }
}
