<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

use App\ServiceInterface\Transport\Order\HttpClientInterface;
use App\ServiceInterface\Transport\Order\OrderClientInterface;

final class OrderClient implements OrderClientInterface
{
    public function __construct(private HttpClientInterface $http)
    {
    }

    public function createOrder(int $totalAmount, string $currency, string $customerId): Order
    {
        [$code, $data] = $this->http->post('/api/v1/orders', [
            'totalAmount' => $totalAmount,
            'currency' => $currency,
            'customerId' => $customerId,
        ]);

        if (201 !== $code) {
            throw new \RuntimeException('Create failed: '.json_encode($data, JSON_THROW_ON_ERROR));
        }

        return Order::fromArray($data);
    }

    public function getOrder(string $orderId): Order
    {
        [$code, $data] = $this->http->get('/api/v1/orders/'.$orderId);

        if (200 !== $code) {
            throw new \RuntimeException('Get failed: '.json_encode($data, JSON_THROW_ON_ERROR));
        }

        return Order::fromArray($data);
    }

    public function transition(string $orderId, string $action, ?string $idempotencyKey = null): void
    {
        $headers = null !== $idempotencyKey ? ['Idempotency-Key: '.$idempotencyKey] : [];
        [$code] = $this->http->post('/api/v1/orders/'.$orderId.'/transitions/'.$action, [], $headers);

        if (204 !== $code) {
            throw new \RuntimeException('Transition failed code='.$code);
        }
    }
}
