<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'order_payment_transaction')]
class OrderPaymentTransaction
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private readonly string $id;

    #[ORM\Column(length: 64)]
    private readonly string $orderId;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private readonly string $amount;

    #[ORM\Column(length: 32)]
    private readonly string $method;

    #[ORM\Column(length: 32)]
    private string $status = 'pending';

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $txId = null;

    public function __construct(string $orderId, string|int|float $amount, string $method)
    {
        $this->id = Uuid::v7()->toRfc4122();
        $this->orderId = $orderId;
        $this->amount = number_format((float) $amount, 2, '.', '');
        $this->method = $method;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function succeed(string $txId): void
    {
        $this->txId = $txId;
        $this->status = 'succeeded';
    }
}
