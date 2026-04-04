<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final class Client
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

    private function headers(array $h = []): array
    {
        if ($this->token) {
            $h['Authorization'] = 'Bearer '.$this->token;
        }

return $h;
    }

    /** @return array{id:string,status:string,totalAmount:int,currency:string,customerId?:string} */
    public function createOrder(int $totalAmount, string $currency, string $customerId, array $meta = []): array
    {
        [$c,$b] = Http::req('POST', $this->base.'/api/v1/orders', $this->headers(['Content-Type' => 'application/json']), ['totalAmount' => $totalAmount, 'currency' => $currency, 'customerId' => $customerId, 'meta' => $meta], $this->timeout);
        if (201 !== $c) {
            throw new \RuntimeException('create failed: '.$c.' '.($b ?: ''));
        }

        return json_decode($b, true);
    }

    public function getOrder(string $id): array
    {
        [$c,$b] = Http::req('GET', $this->base.'/api/v1/orders/'.rawurlencode($id), $this->headers(), null, $this->timeout);
        if (200 !== $c) {
            throw new \RuntimeException('get failed: '.$c.' '.($b ?: ''));
        }

        return json_decode($b, true);
    }

    public function transition(string $id, string $action, ?string $idemKey = null): void
    {
        $h = $this->headers();
        if ($idemKey) {
            $h['Idempotency-Key'] = $idemKey;
        }
        [$c,$b] = Http::req('POST', $this->base.'/api/v1/orders/'.rawurlencode($id).'/transitions/'.rawurlencode($action), $h, '', $this->timeout);
        if (204 !== $c) {
            throw new \RuntimeException('transition failed: '.$c.' '.($b ?: ''));
        }
    }

    public function authorizePayment(string $orderId, int $amount, string $currency): array
    {
        [$c,$b] = Http::req('POST', $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/authorize', $this->headers(['Content-Type' => 'application/json']), ['amount' => $amount, 'currency' => $currency], $this->timeout);
        if ($c < 200 || $c >= 300) {
            throw new \RuntimeException('authorize failed: '.$c.' '.($b ?: ''));
        }

        return json_decode($b, true);
    }

    public function capturePayment(string $orderId, string $providerPaymentId, int $amount): array
    {
        [$c,$b] = Http::req('POST', $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/capture', $this->headers(['Content-Type' => 'application/json']), ['providerPaymentId' => $providerPaymentId, 'amount' => $amount], $this->timeout);
        if ($c < 200 || $c >= 300) {
            throw new \RuntimeException('capture failed: '.$c.' '.($b ?: ''));
        }

        return json_decode($b, true);
    }

    public function refundPayment(string $orderId, string $providerPaymentId, int $amount): array
    {
        [$c,$b] = Http::req('POST', $this->base.'/api/v1/orders/'.rawurlencode($orderId).'/payments/refund', $this->headers(['Content-Type' => 'application/json']), ['providerPaymentId' => $providerPaymentId, 'amount' => $amount], $this->timeout);
        if ($c < 200 || $c >= 300) {
            throw new \RuntimeException('refund failed: '.$c.' '.($b ?: ''));
        }

        return json_decode($b, true);
    }
}
