<?php

declare(strict_types=1);

namespace App\ServiceInterface\Transport\Order;

interface PaymentProviderInterface
{
    public function authorize(string $orderId, int $amount, string $currency, array $meta = []): array;

    public function capture(string $paymentId, int $amount): array;

    public function refund(string $paymentId, int $amount): array;

    public function verifyWebhook(string $payload, string $signatureHeader): bool;

    public function mapEvent(array $event): array;
}
