<?php

declare(strict_types=1);

namespace App\Ordering\Service\Transport\Order;

final readonly class Client
{
    private string $base;
    private ?string $token;
    private int $timeout;

    public function __construct(string $baseUrl, ?string $authToken = null, int $timeout = 10)
    {
        $this->base = rtrim($baseUrl, '/');
        $this->token = $authToken;
        $this->timeout = $timeout;
    }

    private function headers(array $headers = []): array
    {
        if (null !== $this->token) {
            $headers['Authorization'] = 'Bearer '.$this->token;
        }

        return $headers;
    }

    /**
     * @return array{id:string,status:string,totalAmount:int,currency:string,customerId?:string}
     *
     * @throws \JsonException
     */
    public function createOrder(int $totalAmount, string $currency, string $customerId, array $meta = []): array
    {
        [$code, $body] = Http::req(
            'POST',
            $this->base.'/api/v1/orders',
            $this->headers(['Content-Type' => 'application/json']),
            [
                'totalAmount' => $totalAmount,
                'currency' => $currency,
                'customerId' => $customerId,
                'meta' => $meta,
            ],
            $this->timeout,
        );

        if (201 !== $code) {
            throw new \RuntimeException('create failed: '.$code.' '.($body ?: ''));
        }

        return json_decode($body, true);
    }

    public function getOrder(string $id): array
    {
        [$code, $body] = Http::req(
            'GET',
            $this->base.'/api/v1/orders/'.rawurlencode($id),
            $this->headers(),
            null,
            $this->timeout,
        );

        if (200 !== $code) {
            throw new \RuntimeException('get failed: '.$code.' '.($body ?: ''));
        }

        return json_decode($body, true);
    }

    public function transition(string $id, string $action, ?string $idemKey = null): void
    {
        $headers = $this->headers();

        if (null !== $idemKey) {
            $headers['Idempotency-Key'] = $idemKey;
        }

        [$code, $body] = Http::req(
            'POST',
            $this->base.'/api/v1/orders/'.rawurlencode($id).'/transitions/'.rawurlencode($action),
            $headers,
            '',
            $this->timeout,
        );

        if (204 !== $code) {
            throw new \RuntimeException('transition failed: '.$code.' '.($body ?: ''));
        }
    }

    public function authorizePayment(string $orderId, int $amount, string $currency): array
    {
        [$code, $body] = Http::req(
            'POST',
            $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/authorize',
            $this->headers(['Content-Type' => 'application/json']),
            ['amount' => $amount, 'currency' => $currency],
            $this->timeout,
        );

        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException('authorize failed: '.$code.' '.($body ?: ''));
        }

        return json_decode($body, true);
    }

    public function capturePayment(string $orderId, string $providerPaymentId, int $amount): array
    {
        [$code, $body] = Http::req(
            'POST',
            $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/capture',
            $this->headers(['Content-Type' => 'application/json']),
            ['providerPaymentId' => $providerPaymentId, 'amount' => $amount],
            $this->timeout,
        );

        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException('capture failed: '.$code.' '.($body ?: ''));
        }

        return json_decode($body, true);
    }

    public function refundPayment(string $orderId, string $providerPaymentId, int $amount): array
    {
        [$code, $body] = Http::req(
            'POST',
            $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/refund',
            $this->headers(['Content-Type' => 'application/json']),
            ['providerPaymentId' => $providerPaymentId, 'amount' => $amount],
            $this->timeout,
        );

        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException('refund failed: '.$code.' '.($body ?: ''));
        }

        return json_decode($body, true);
    }
}
