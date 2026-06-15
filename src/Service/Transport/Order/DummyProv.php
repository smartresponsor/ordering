<?php

declare(strict_types=1);

namespace App\Service\Transport\Order;

use App\ServiceInterface\Transport\Order\PaymentProviderInterface;

final readonly class DummyProv implements PaymentProviderInterface
{
    public function __construct(
        private string $nameEntity,
        private float $failPct = 0.0,
        private int $minMs = 10,
        private int $maxMs = 30,
    ) {
    }

    public function authorize(string $orderId, int $amount, string $currency, array $meta = []): array
    {
        usleep(random_int($this->minMs, $this->maxMs) * 1000);

        if (random_int(1, 100) <= (int) round($this->failPct * 100)) {
            throw new \RuntimeException('dummy fail');
        }

        return ['provider' => $this->nameEntity, 'status' => 'ok'];
    }

    public function capture(string $paymentId, int $amount): array
    {
        return ['provider' => $this->nameEntity, 'status' => 'ok'];
    }

    public function refund(string $paymentId, int $amount): array
    {
        return ['provider' => $this->nameEntity, 'status' => 'ok'];
    }

    public function verifyWebhook(string $payload, string $signatureHeader): bool
    {
        return true;
    }

    public function mapEvent(array $event): array
    {
        return ['nameEntity' => 'dummy', 'payload' => $event];
    }
}
