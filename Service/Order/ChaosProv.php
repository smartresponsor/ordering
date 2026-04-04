<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Order\PaymentProviderInterface;

final class ChaosProv implements PaymentProviderInterface
{
    public function __construct(
        private string $name,
        private float $failPct = 0.0,
        private int $minMs = 10,
        private int $maxMs = 30,
        private float $timeoutPct = 0.0,
        private int $timeoutMs = 500,
    ) {
    }

    public function authorize(string $orderId, int $amount, string $currency, array $meta = []): array
    {
        $sleepMs = random_int($this->minMs, $this->maxMs);

        if (random_int(1, 100) <= (int) round($this->timeoutPct * 100)) {
            usleep($this->timeoutMs * 1000);
            throw new \RuntimeException('timeout');
        }

        usleep($sleepMs * 1000);

        if (random_int(1, 100) <= (int) round($this->failPct * 100)) {
            throw new \RuntimeException('provider failure');
        }

        return ['provider' => $this->name, 'status' => 'ok'];
    }

    public function capture(string $paymentId, int $amount): array
    {
        return ['provider' => $this->name, 'status' => 'ok'];
    }

    public function refund(string $paymentId, int $amount): array
    {
        return ['provider' => $this->name, 'status' => 'ok'];
    }

    public function verifyWebhook(string $payload, string $signatureHeader): bool
    {
        return true;
    }

    public function mapEvent(array $event): array
    {
        return ['name' => 'chaos', 'payload' => $event];
    }
}
