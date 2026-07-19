<?php

declare(strict_types=1);

namespace App\Service\Payment\Order;

use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\ServiceInterface\Payment\Order\PaymentStateMachineInterface;

final class PaymentStateMachine implements PaymentStateMachineInterface
{
    public function authorize(OrderPaymentEntity $payment): void
    {
        $this->transit($payment, 'authorized');
    }

    public function capture(OrderPaymentEntity $payment): void
    {
        $this->transit($payment, 'captured');
    }

    public function settle(OrderPaymentEntity $payment): void
    {
        $this->transit($payment, 'settled');
    }

    public function void(OrderPaymentEntity $payment): void
    {
        $this->transit($payment, 'voided');
    }

    public function refund(OrderPaymentEntity $payment): void
    {
        $this->transit($payment, 'refunded');
    }

    private function transit(OrderPaymentEntity $payment, string $targetStatus): void
    {
        $from = $payment->status();
        $allowed = match ($from) {
            'initiated' => ['authorized', 'voided'],
            'authorized' => ['captured', 'voided'],
            'captured' => ['settled', 'refunded'],
            'settled', 'voided', 'refunded' => [],
            default => throw new \InvalidArgumentException(sprintf('Unknown payment status "%s".', $from)),
        };

        if ($from === $targetStatus) {
            return;
        }

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \InvalidArgumentException(sprintf('Transition from %s to %s is not allowed.', $from, $targetStatus));
        }

        $payment->setStatus($targetStatus);
    }
}
