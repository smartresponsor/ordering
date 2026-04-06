<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class OrderPaymentTransaction
{
    private string $id;
    private string $orderId;
    private string $amount;
    private string $method;
    private string $status = 'pending';
    private ?string $txId = null;

    public function __construct(string $orderId, string|int|float $amount, string $method)
    {
        $this->id = \Symfony\Component\Uid\Uuid::v7()->toRfc4122();
        $this->orderId = $orderId;
        $this->amount = number_format((float) $amount, 2, '.', '');
        $this->method = $method;
    }

    public function id(): string { return $this->id; }
    public function orderId(): string { return $this->orderId; }
    public function amount(): string { return $this->amount; }
    public function method(): string { return $this->method; }
    public function status(): string { return $this->status; }
    public function succeed(string $txId): void { $this->txId = $txId; $this->status = 'succeeded'; }
}
