<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

use App\ServiceInterface\Transport\Order\PaymentProviderInterface;

final readonly class ChaosProv implements PaymentProviderInterface
{
    public function __construct(
        private readonly string $name,
        private readonly float $failPct = 0.0,
        private readonly int $minMs = 10,
        private readonly int $maxMs = 30,
        private readonly float $timeoutPct = 0.0,
        private readonly int $timeoutMs = 500,
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
