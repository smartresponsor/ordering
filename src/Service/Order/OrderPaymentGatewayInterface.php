<?php

declare(strict_types=1);

namespace App\Service\Order;

use PaymentComponent\Entity\Payment\Payment;

interface OrderPaymentGatewayInterface
{
    public function initiatePayment(string $orderNumber, float $amount, string $currency): Payment;

    public function refundPayment(string $orderNumber, float $amount): bool;

    public function getPaymentStatus(string $orderNumber): ?string;
}
